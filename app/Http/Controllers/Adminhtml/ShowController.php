<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowRequest;
use App\Repositories\Interfaces\CinemaRepositoryInterface;
use App\Seat;
use App\Show;
use App\Repositories\Interfaces\ShowRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Class ShowController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class ShowController extends Controller
{
    /**
     * @var ShowRepositoryInterface
     */
    private $showRepository;

    /**
     * @var CinemaRepositoryInterface
     */
    private $cinemaRepository;

    /**
     * ShowController constructor.
     *
     * @param ShowRepositoryInterface $showRepository
     * @param CinemaRepositoryInterface $cinemaRepository
     */
    public function __construct(
        ShowRepositoryInterface $showRepository,
        CinemaRepositoryInterface $cinemaRepository
    ) {
        $this->showRepository = $showRepository;
        $this->cinemaRepository = $cinemaRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Show::class);

        /** @var Collection $shows */
        $shows = $this->showRepository->all(['cinema']);

        if (request()->ajax()) {
            $dt = datatables()->of($shows);

            $authU = auth()->user();


            $dt->editColumn('status', function (Show $show) use ($authU) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$show->getStatus() === Show::ENABLE ? "checked " : "";
                $htmlRaw .= "class=\"status-checkbox\"".
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

                        $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
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

            if ($authU->can('view', Seat::class)) {
                $htmlRaw = "";

                $dt->addColumn('seats_list', function (Show $show) use ($htmlRaw) {
                    $htmlRaw .= "<a href=\"" . route('shows.getSeats', ['show' => $show->getId()]) . "\"
                                   type=\"button\" class=\"";

                    $htmlRaw .= "col-md-12 btn dmovie-btn dmovie-btn-success\"";
                    $htmlRaw .= "title=\"" . __('View seats list') . "\">";
                    $htmlRaw .= "<i class=\"mdi mdi-eye\"></i></a>";

                    return $htmlRaw;
                });
            } else {
                $dt->addColumn('seats list', '');
            }

            return $dt->rawColumns(['status', 'task', 'seats_list'])->make();
        }

        return view('admin.show.index', compact('shows'));
    }

    /**
     * Get seats by show
     *
     * @param Show $show
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getSeats(Show $show)
    {
        $this->authorize('view', Seat::class);

        /** @var Collection $seats */
        $seats = $show->seats();
        try {
            $authU = auth()->user();

            if (request()->ajax()) {
                $dt = datatables()->of($seats);

                $dt->editColumn('status', function (Seat $seat) use ($authU) {
                    $htmlRaw = "<div class=\"dmovie-flex-container\">";
                    $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                    $htmlRaw .= "<input type=\"checkbox\"";
                    $htmlRaw .= (int)$seat->getStatus() === Seat::ENABLE ? "checked " : "";
                    $htmlRaw .= "class=\"status-checkbox\"".
                        "value=\"{$seat->getId()}\"".
                        "data-id=\"{$seat->getId()}\"";
                    if ($authU->cant('update', Seat::class)) {
                        $htmlRaw .= "disabled";
                    }
                    $htmlRaw .= " dmovie-switch-dt/>";
                    $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$seat->getStatusLabel()}
                          </label>
                    </div>
                </div></div>";

                    return $htmlRaw;
                });

                if ($authU->can('canEditDelete', Seat::class)) {
                    $htmlRaw = "";
                    $dt->addColumn('task', function (Seat $seat) use ($authU, $htmlRaw) {
                        if ($authU->can('update', Seat::class)) {
                            $htmlRaw .= "<a href=\"" . route('seats.edit', ['seat' => $seat->getId()]) . "\"
                                   type=\"button\" class=\"";
                            if ($authU->cant('delete', Seat::class)) {
                                $htmlRaw .= "col-md-12 ";
                            } else {
                                $htmlRaw .= 'col-md-6 ';
                            }

                            $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                            $htmlRaw .= "title=\"" . __('Detail') . "\">";
                            $htmlRaw .= "<i class=\"fa fa-pencil-square-o\"></i></a>";
                        }

                        if ($authU->can('delete', Seat::class)) {
                            $cssClass = $authU->can('update', Seat::class) ? "col-md-6" : "col-md-12";

                            $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
                                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\" " . __('Delete') . " \"
                                            data-id=\"{$seat->getId()}\"
                                            url=\"" . route('seats.destroy', ['seat' => $seat->getId()]) . "\">
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

            return view('admin.seat.index_by_show', compact('show'));
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
     * Create seat
     *
     * @param Show $show
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createSeats(Show $show)
    {
        $this->authorize('create', Seat::class);

        return view('admin.seat.create_by_show', compact('show'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Show::class);


        $cinemas = $this->cinemaRepository->all();
        return view('admin.show.create', compact('cinemas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $cinemaId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createWithCinema(int $cinemaId)
    {
        $this->authorize('create', Show::class);

        $cinema = $this->cinemaRepository->find($cinemaId);
        return view('admin.show.create_with_cinema', compact('cinema'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(ShowRequest $request)
    {
        $this->authorize('create', Show::class);

        try {
            /** @var Show $show */
            $show = $this->showRepository->create($request->all());

            return back()->with('success', __(
                'The :sName show have created.',
                ['sName' => $show->getName()]
            ))->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function show(Show $show)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Show $show
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Show $show)
    {
        $this->authorize('update', Show::class);

        return view('admin.show.edit', compact('show'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Show  $show
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(ShowRequest $request, Show $show)
    {
        $this->authorize('update', Show::class);

        try {
            /** @var Show $show */
            $show = $this->showRepository->update(null, $show, $request->all());

            if ($show) {
                return redirect(route('shows.index'))
                    ->with('success', __('The :name show have updated.', ['name' => $show->getName()]));
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $show
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $showId)
    {
        $this->authorize('delete', Show::class);

        try {
            /** @var Show $show */
            $show = $this->showRepository->delete($showId, null);

            $message = __('The show :name have deleted.', ['name' => $show->getName()]);
            return !request()->ajax() ?
                redirect(route('shows.index'))
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
     * Fetch visible show to select2
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function attemptSelect2()
    {
        $this->authorize('view', Show::class);

        try {
            if (request()->has('data')) {
                if (array_key_exists('cinema', request('data'))) {
                    $cinemaId = request('data')['cinema'];

                    if (request()->has('search_key')) {
                        $searchKey = request('search_key');

                        $collection = $this->showRepository->searchBy(null, [
                            Show::NAME => $searchKey,
                            Show::CINEMA_ID => $cinemaId
                        ]);

                        $collection = $this->showRepository->getVisible($collection);

                        return request()->ajax() ?
                            response()->json([
                                'status' => 200,
                                'data' => $collection->get()
                            ]) :
                            $collection->get();
                    }

                    $collection = $this->showRepository->searchBy(null, ['cinema_id' => $cinemaId]);
                    $collection = $this->showRepository->getVisible($collection);
                }
            } else {
                $collection = $this->showRepository->getVisible();
            }

            return request()->ajax() ?
                response()->json([
                    'status' => 200,
                    'data' => $collection->get()
                ]) :
                $collection->get();

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
     * Mass destroy cinemas
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massDestroy()
    {
        $this->authorize('delete', Show::class);

        try {
            $shows = request('shows');

            $deletedCount = 0;

            /** @var string $show */
            foreach ($shows as $show) {
                $this->showRepository->delete($show, null);

                $deletedCount++;
            }

            $message = __(':num shows have deleted.', ['num' => $deletedCount]);
            return !request()->ajax() ?
                redirect(route('shows.index'))
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
        $this->authorize('update', Show::class);

        try {
            $updatedShowCount = 0;

            $shows = request('shows');
            $fields = request('fields');

            foreach ($shows as $show) {
                if (!$this->showRepository->update($show, null, $fields)) {
                    throw new \Exception(__('We can update cinema have id :id', ['id' => $show]));
                }
                $updatedShowCount++;
            }

            $message = __(':num shows have updated.', ['num' => $updatedShowCount]);
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
     * Get show by id
     *
     * @param Show $show
     * @return Show|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getShow(Show $show)
    {
        $this->authorize('view', Show::class);

        return request()->ajax() ?
            response()->json([
                'status' => 200,
                'data' => $show
            ]) :
            $show;
    }
}
