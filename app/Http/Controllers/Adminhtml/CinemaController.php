<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use App\Http\Requests\CinemaRequest;
use App\Repositories\Interfaces\CinemaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Cinema;
use App\Show;

/**
 * Class CinemaController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class CinemaController extends Controller
{
    /**
     * @var CinemaRepositoryInterface
     */
    private $cinemaRepository;

    /**
     * CinemaController constructor.
     *
     * @param CinemaRepositoryInterface $cinemaRepository
     */
    public function __construct(
        CinemaRepositoryInterface $cinemaRepository
    ) {
        $this->cinemaRepository = $cinemaRepository;
    }

    /**
     * Get list shows of cinema
     *
     * @param int $cinemaId
     * @return Collection
     * @throws \Exception
     */
    public function getShows(int $cinemaId)
    {
        $this->authorize('view', Show::class);

        $shows = $this->cinemaRepository->find($cinemaId)->shows();
        $dt = datatables()->of($shows);
        $authU = auth()->user();

        if ($authU->can('canEditDelete', Show::class)) {
            $htmlRaw = "";
            $dt->addColumn('task', function (Show $show) use ($authU, $htmlRaw) {
                if ($authU->can('update', Show::class)) {
                    $htmlRaw .= "<a href=\"" . route('shows.edit', ['show' => $show->getId()]) . "\"
                                   type=\"button\" class=\"";
                    if ($authU->cant('delete', Show::class)) {
                        $htmlRaw .= "col-md-12 ";
                    } else {
                        $htmlRaw .= 'col-md-6 ';
                    }

                    $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                    $htmlRaw .= "title=\"" . __('Detail') . "\">";
                    $htmlRaw .= "<i class=\"fa fa-pencil-square-o\"></i></a>";
                }

                if ($authU->can('delete', Show::class)) {
                    $cssClass = $authU->can('update', Show::class) ? "col-md-6" : "col-md-12";

                    $htmlRaw .= "<button id=\"detailDeleteBtn\" type=\"button\"
                                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\" " . __('Delete') . " \"
                                            data-id=\"{$show->getId()}\"
                                            url=\"" . route('shows.destroy', ['show' => $show->getId()]) . "\">
                                        <i class=\"fa fa-trash-o\"></i>
                                    </button>";
                }

                return $htmlRaw;
            });
        } else {
            $dt->addColumn('task', '');
        }

        $dt->editColumn('status', function (Show $show) use ($authU) {
            $htmlRaw = "<div class=\"dmovie-flex-container\">";
            $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
            $htmlRaw .= "<input type=\"checkbox\"";
            $htmlRaw .= (int)$show->getStatus() === Show::ENABLE ? "checked " : "";
            $htmlRaw .= "dmovie-details-dt class=\"status-checkbox\"".
                "value=\"{$show->getId()}\"".
                "data-id=\"{$show->getId()}\"";
            if ($authU->cant('update', Show::class)) {
                $htmlRaw .= "disabled";
            }
            $htmlRaw .= "/>";
            $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$show->getStatusLabel()}
                          </label>
                    </div>
                </div></div>";

            return $htmlRaw;
        });
        return $dt->rawColumns(['task', 'status'])->make();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function index()
    {
        $this->authorize('view', Cinema::class);

        $cinemas = $this->cinemaRepository->all();

        if (request()->ajax()) {
            $dt = datatables()->of($cinemas);

            $dt->addColumn('shows_url', function (Cinema $cinema) {
                return route('cinemas.getShows', ['cinema' => $cinema->getId()]);
            });

            $authU = auth()->user();

            $dt->editColumn('status', function (Cinema $cinema) use ($authU) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$cinema->getStatus() === Cinema::ENABLE ? "checked " : "";
                $htmlRaw .= "dmovie-switch-dt class=\"status-checkbox\"".
                    "value=\"{$cinema->getId()}\"".
                    "data-id=\"{$cinema->getId()}\"";
                if ($authU->cant('update', Cinema::class)) {
                    $htmlRaw .= "disabled";
                }
                $htmlRaw .= "/>";
                $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$cinema->getStatusLabel()}
                          </label>
                    </div>
                </div></div>";

                return $htmlRaw;
            });


            if ($authU->can('canEditDelete', Cinema::class)) {
                $htmlRaw = "";
                $dt->addColumn('task', function (Cinema $cinema) use ($authU, $htmlRaw) {
                    if ($authU->can('update', Cinema::class)) {
                        $htmlRaw .= "<a href=\"" . route('cinemas.edit', ['cinema' => $cinema->getId()]) . "\"
                                   type=\"button\" class=\"";
                        if ($authU->cant('delete', Cinema::class)) {
                            $htmlRaw .= "col-md-12 ";
                        } else {
                            $htmlRaw .= 'col-md-6 ';
                        }

                        $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                        $htmlRaw .= "title=\"" . __('Detail') . "\">";
                        $htmlRaw .= "<i class=\"fa fa-pencil-square-o\"></i></a>";
                    }

                    if ($authU->can('delete', Cinema::class)) {
                        $cssClass = $authU->can('update', Cinema::class) ? "col-md-6" : "col-md-12";

                        $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
                                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\" " . __('Delete') . " \"
                                            data-id=\"{$cinema->getId()}\"
                                            url=\"" . route('cinemas.destroy', ['cinema' => $cinema->getId()]) . "\">
                                        <i class=\"fa fa-trash-o\"></i>
                                    </button>";
                    }

                    return $htmlRaw;
                });
            } else {
                $dt->addColumn('task', '');
            }




            return $dt->rawColumns(['status', 'task'])->make();
        }

        return view('admin.cinema.index', compact('cinemas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Cinema::class);

        return view('admin.cinema.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CinemaRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CinemaRequest $request)
    {
        $this->authorize('create', Cinema::class);

        try {
            /** @var Cinema $cinema */
            $cinema = $this->cinemaRepository->create($request->all());

            return redirect(route('cinemas.index'))
                ->with('success', __('The :name have created.', ['name' => $cinema->getName()]));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cinema  $cinema
     * @return \Illuminate\Http\Response
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $cinemaId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(int $cinemaId)
    {
        $this->authorize('update', Cinema::class);

        $cinema = $this->cinemaRepository->find($cinemaId);

        return view('admin.cinema.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Cinema $cinema
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CinemaRequest $request, int $cinema)
    {
        $this->authorize('update', Cinema::class);

        try {
            /** @var Cinema $cinema */
            $cinema = $this->cinemaRepository->update($cinema, null, $request->all());
            if ($cinema) {
                return redirect(route('cinemas.index'))
                    ->with('success', __('The :name cinema have updated.', ['name' => $cinema->getName()]));
            }
            throw new \Exception(__('We cant complete this action.'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $cinemaId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $cinemaId)
    {
        $this->authorize('delete', Cinema::class);

        try {
            /** @var Cinema $cinema */
            $cinema = $this->cinemaRepository->delete($cinemaId, null);

            $message = __('The cinema :name have deleted.', ['name' => $cinema->getName()]);
            return !request()->ajax() ?
                redirect(route('cinemas.index'))
                    ->with('success', $message) :
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]);
        } catch (Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return !request()->ajax() ?
                back()->with(
                    'error',
                    $message
                ) :
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]);

        }
    }


    /**
     * Mass destroy cinemas
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massDestroy()
    {
        $this->authorize('delete', Cinema::class);

        try {
            $cinemas = request('cinemas');

            $deletedCinemasCount = 0;

            /** @var string $film */
            foreach ($cinemas as $cinema) {
                /** @var Cinema $cinemas */
                $this->cinemaRepository->delete($cinema, null);

                $deletedCinemasCount++;
            }

            $message = __(':num cinemas have deleted.', ['num' => $deletedCinemasCount]);
            return !request()->ajax() ?
                redirect(route('cinemas.index'))
                    ->with('success', $message) :
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]);
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return !request()->ajax() ?
                back()->with(
                    'error',
                    $message
                ) :
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]);
        }
    }

    /**
     * Multi update
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massUpdate()
    {
        $this->authorize('update', Cinema::class);

        try {
            $deletedCinemasCount = 0;

            $cinemas = request('cinemas');
            $fields = request('fields');

            foreach ($cinemas as $cinema) {
                if (!$this->cinemaRepository->update($cinema, null, $fields)) {
                    throw new \Exception(__('We can update cinema have id :id', ['id' => $cinema]));
                }
                $deletedCinemasCount++;
            }

            $message = __(':num cinemas have updated.', ['num' => $deletedCinemasCount]);
            return !request()->ajax() ?
                redirect(route('cinemas.index'))
                    ->with('success', $message) :
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]);
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return !request()->ajax() ?
                back()->with(
                    'error',
                    $message
                ) :
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]);
        }
    }
}
