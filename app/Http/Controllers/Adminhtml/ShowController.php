<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowRequest;
use App\Repositories\Interfaces\CinemaRepositoryInterface;
use App\Repositories\Interfaces\ShowInterface as Show;
use App\Repositories\Interfaces\ShowRepositoryInterface;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd($this->showRepository->all());
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
     * @param  \App\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function edit(Show $show)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Show $show)
    {
        //
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
}
