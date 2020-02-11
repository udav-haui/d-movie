<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Requests\FilmRequest;
use App\Repositories\Interfaces\FilmInterface as Film;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use Exception;
use Illuminate\Http\Request;

/**
 * Class FilmController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class FilmController extends Controller
{
    /**
     * @var FilmRepositoryInterface
     */
    private $filmRepository;

    /**
     * FilmController constructor.
     *
     * @param FilmRepositoryInterface $filmRepository
     */
    public function __construct(FilmRepositoryInterface $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Film::class);

        $films = $this->filmRepository->all();

        if (request()->ajax()) {
            $dt = datatables()->of($films);

            $dt->editColumn('trailer', function (Film $film) {
                return "<div class=\"dmovie-flex-container\">".
                        "<a dm-fancybox data-fancybox=\"trailer\" class=\"dmovie-fancybox-media slide-item\"".
                        "href=\"{$film->getTrailer()}\">
                            <img class=\"card-img-top img-fluid slide-item-image\"".
                        " title=\"" . __('Preview trailer') ." - " .
                        "{$film->getTitle()}\" src=\"{$film->getPosterPath()}\" />
                        </a>".
                        "</div>";
            });

            $dt->editColumn('poster', function (Film $film) {
                return '<div class="dmovie-flex-container">' . $film->getRenderHtmlPoster() . '</div>';
            });

            $authU = auth()->user();

            $dt->editColumn('status', function (Film $film) use ($authU) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$film->getStatus() === Film::ENABLE ? "checked " : "";
                $htmlRaw .= "class=\"status-checkbox\"".
                    "value=\"{$film->getId()}\"".
                    "data-id=\"{$film->getId()}\"";
                if ($authU->cant('update', Film::class)) {
                    $htmlRaw .= "disabled";
                }
                $htmlRaw .= "/>";
                $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$film->getStatusLabel()}
                          </label>
                    </div>
                </div></div>";

                return $htmlRaw;
            });


            if ($authU->can('canEditDelete', Film::class)) {
                $htmlRaw = "";
                $dt->addColumn('task', function (Film $film) use ($authU, $htmlRaw) {
                    if ($authU->can('update', Film::class)) {
                        $htmlRaw .= "<a href=\"" . route('films.edit', ['film' => $film->getId()]) . "\"
                                   type=\"button\" class=\"";
                        if ($authU->cant('delete', Film::class)) {
                            $htmlRaw .= "col-md-12 ";
                        } else {
                            $htmlRaw .= 'col-md-6 ';
                        }

                        $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                        $htmlRaw .= "title=\"" . __('Detail') . "\">";
                        $htmlRaw .= "<i class=\"fa fa-pencil-square-o\"></i></a>";
                    }

                    if ($authU->can('delete', Film::class)) {
                        $cssClass = $authU->can('update', Film::class) ? "col-md-6" : "col-md-12";

                        $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
                                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\" " . __('Delete') . " \"
                                            data-id=\"{$film->getId()}\"
                                            url=\"" . route('films.destroy', ['film' => $film->getId()]) . "\">
                                        <i class=\"fa fa-trash-o\"></i>
                                    </button>";
                    }

                    return $htmlRaw;
                });
            } else {
                $dt->addColumn('task', '');
            }




            return $dt->rawColumns(['poster', 'status', 'trailer', 'task'])->make();
        }

        return view('admin.film.index', compact('films'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Film::class);

        return view('admin.film.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(FilmRequest $request)
    {
        $this->authorize('create', Film::class);

        try {

            /** @var Film $film */
            $film = $this->filmRepository->create($request->all());

            return redirect(route('films.index'))
                ->with('success', __('The :name have created.', ['name' => $film->getTitle()]));

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function show(Film $film)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $filmId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(int $filmId)
    {
        $this->authorize('update', Film::class);
        $film = $this->filmRepository->find($filmId);
        return view('admin.film.edit', compact('film'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FilmRequest $request
     * @param int $filmId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(FilmRequest $request, int $filmId)
    {
        $this->authorize('update', Film::class);

        try {
            /** @var Film $film */
            $film = $this->filmRepository->update($filmId, null, $request->all());

            return redirect(route('films.index'))
                ->with('success', __('The :name have updated.', ['name' => $film->getTitle()]));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $filmId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $filmId)
    {
        $this->authorize('delete', Film::class);

        try {
            /** @var Film $film */
            $film = $this->filmRepository->delete($filmId, null);

            $message = __('The :name film have deleted.', ['name' => $film->getTitle()]);
            return !request()->ajax() ?
                redirect(route('films.index'))
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
     * Mass destroy film
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massDestroy()
    {
        $this->authorize('delete', Film::class);

        try {
            $films = request('films');

            $deletedFilmCount = 0;

            /** @var string $film */
            foreach ($films as $film) {
                /** @var Film $film */
                $this->filmRepository->delete($film, null);

                $deletedFilmCount++;
            }

            $message = __(':num films have deleted.', ['num' => $deletedFilmCount]);
            return !request()->ajax() ?
                redirect(route('films.index'))
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
     * Multi update
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massUpdate()
    {
        $this->authorize('update', Film::class);

        try {
            $deletedFilmCount = 0;

            $films = request('films');
            $fields = request('fields');

            foreach ($films as $film) {
                if (!$this->filmRepository->update($film, null, $fields)) {
                    throw new Exception(__('We can update film have id :id', ['id' => $film]));
                }
                $deletedFilmCount++;
            }

            $message = __(':num films have updated.', ['num' => $deletedFilmCount]);
            return !request()->ajax() ?
                redirect(route('films.index'))
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
}
