<?php

namespace App\Http\Controllers\Adminhtml;

use App\Repositories\Interfaces\FilmInterface as Film;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use Illuminate\Http\Request;

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
                        $htmlRaw .= "<i class=\"mdi mdi-account-edit\"></i></a>";
                    }

                    if ($authU->can('delete', Film::class)) {
                        $cssClass = $authU->can('update', Film::class) ? "col-md-6" : "col-md-12";

                        $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
                                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\" " . __('Delete') . " \"
                                            data-id=\"{$film->getId()}\"
                                            url=\"" . route('films.destroy', ['film' => $film->getId()]) . "\">
                                        <i class=\"mdi mdi-account-minus\"></i>
                                    </button>";
                    }

                    return $htmlRaw;
                });
            } else {
                $dt->addColumn('task', '');
            }




            return $dt->rawColumns(['poster', 'status', 'task'])->make();
        }

        return view('admin.film.index', compact('films'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \App\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function edit(Film $film)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Film $film)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function destroy(Film $film)
    {
        //
    }
}
