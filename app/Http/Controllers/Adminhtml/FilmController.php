<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Requests\FilmRequest;
use App\Film;
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

            $dt->editColumn('release_date', function (Film $film) {
                return $film->getFormattedDate();
            });

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

            $authUser = auth()->user();

            $dt->editColumn('status', function (Film $film) use ($authUser) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$film->getStatus() === Film::ENABLE ? "checked " : "";
                $htmlRaw .= "class=\"status-checkbox\"".
                    "value=\"{$film->getId()}\"".
                    "data-id=\"{$film->getId()}\"";
                if ($authUser->cant('update', Film::class)) {
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



            $dt->editColumn('is_coming_soon', function (Film $film) use ($authUser) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$film->getIsComingSoon() === Film::YES ? "checked " : "";
                $htmlRaw .= "class=\"status--checkbox\"".
                    "value=\"{$film->getId()}\"".
                    "url=\"" . route('films.massUpdate') . "\"".
                    "data-field=\"is_coming_soon\"  ".
                    "data-id=\"{$film->getId()}\"";
                if ($authUser->cant('update', Film::class)) {
                    $htmlRaw .= "disabled";
                }
                $htmlRaw .= " dmovie-switch--dt/>";
                $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$film->getIsComingSoonLabel()}
                          </label>
                    </div>
                </div></div>";

                return $htmlRaw;
            });

            $dt->editColumn('is_open_sale_ticket', function (Film $film) use ($authUser) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$film->getIsOpenSaleTicket() === Film::YES ? "checked " : "";
                $htmlRaw .= "class=\"status--checkbox\"".
                    "value=\"{$film->getId()}\"".
                    "url=\"" . route('films.massUpdate') . "\"".
                    "data-field=\"is_open_sale_ticket\"  ".
                    "data-id=\"{$film->getId()}\"";
                if ($authUser->cant('update', Film::class)) {
                    $htmlRaw .= "disabled";
                }
                $htmlRaw .= " dmovie-switch--dt/>";
                $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$film->getIsOpenSaleTicketLabel()}
                          </label>
                    </div>
                </div></div>";

                return $htmlRaw;
            });

            $dt->editColumn('is_sneak_show', function (Film $film) use ($authUser) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$film->getIsSneakShow() === Film::YES ? "checked " : "";
                $htmlRaw .= "class=\"status--checkbox\"".
                    "value=\"{$film->getId()}\"".
                    "url=\"" . route('films.massUpdate') . "\"".
                    "data-field=\"is_sneak_show\"  ".
                    "data-id=\"{$film->getId()}\"";
                if ($authUser->cant('update', Film::class)) {
                    $htmlRaw .= "disabled";
                }
                $htmlRaw .= " dmovie-switch--dt/>";
                $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$film->getIsSneakShowLabel()}
                          </label>
                    </div>
                </div></div>";

                return $htmlRaw;
            });



            if ($authUser->can('canEditDelete', Film::class)) {
                $htmlRaw = "";
                $dt->addColumn('task', function (Film $film) use ($authUser, $htmlRaw) {
                    if ($authUser->can('update', Film::class)) {
                        $htmlRaw .= "<a href=\"" . route('films.edit', ['film' => $film->getId()]) . "\"
                                   type=\"button\" class=\"";
                        if ($authUser->cant('delete', Film::class)) {
                            $htmlRaw .= "col-md-12 ";
                        } else {
                            $htmlRaw .= 'col-md-6 ';
                        }

                        $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                        $htmlRaw .= "title=\"" . __('Detail') . "\">";
                        $htmlRaw .= "<i class=\"fa fa-pencil-square-o\"></i></a>";
                    }

                    if ($authUser->can('delete', Film::class)) {
                        $cssClass = $authUser->can('update', Film::class) ? "col-md-6" : "col-md-12";

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




            return $dt->rawColumns(
                ['poster', 'status', 'trailer', 'task', 'is_coming_soon', 'is_open_sale_ticket', 'is_sneak_show']
            )->make();
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
                ->with('success', __('The film [:name] have updated.', ['name' => $film->getTitle()]));
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
            $updatedFilm = [];

            $films = request('films');
            $fields = request('fields');

            foreach ($films as $film) {
                /** @var Film $film */
                $film = $this->filmRepository->update($film, null, $fields);
                if (!$film) {
                    throw new Exception(__('We can update film have id :id', ['id' => $film]));
                }
                array_push($updatedFilm, $film->getTitle());
            }

            $message = __('The [:films] films have updated.', ['films' => implode(', ', $updatedFilm)]);
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
     * Get visible film for select2 request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function attemptSelect2()
    {
        $this->authorize('view', Film::class);

        try {
            if (request()->has('search_key')) {
                $collection = $this->filmRepository->searchBy(null, ['title' => request('search_key')]);
                $collection = $this->filmRepository->getVisible($collection);

                return response()->json([
                    'status' => 200,
                    'data' => $collection->get()
                ]);
            }

            $collection = $this->filmRepository->getVisible();

            return response()->json([
                'status' => 200,
                'data' => $collection->get()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get film by ID
     *
     * @param Film $film
     * @return Film|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getFilm(Film $film)
    {
        $this->authorize('view', Film::class);

        return request()->ajax() ?
            response()->json([
                'status' => 200,
                'data' => $film
            ]) :
            $film;
    }
}
