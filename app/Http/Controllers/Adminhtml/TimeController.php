<?php

namespace App\Http\Controllers\Adminhtml;

use App\FilmSchedule;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeRequest;
use App\Repositories\Interfaces\TimeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

/**
 * Class TimeController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class TimeController extends Controller
{
    /**
     * @var TimeRepositoryInterface
     */
    private $timeRepository;

    /**
     * TimeController constructor.
     * @param TimeRepositoryInterface $timeRepository
     */
    public function __construct(
        TimeRepositoryInterface $timeRepository
    ) {
        $this->timeRepository = $timeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(TimeRequest $request)
    {
        $this->authorize('create', FilmSchedule::class);

        try {
            /** @var \App\Time|MessageBag $result */
            $result = $this->timeRepository->create($request->all());

            if ($result instanceof MessageBag) {
                return back()->withErrors($result)->withInput();
            }

            return redirect(route('fs.getShowtime', ['schedule' => $result->getSchedule()]))
                ->with('success', __('The showtime [<code>:time</code>] have created for [<code>:filmName</code>].', [
                    'time' => $result->getStartTime() . ' - ' . $result->getStopTime(),
                    'filmName' => $result->getSchedule()->getFilm()->getTitle()
                ]));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Time $time
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(\App\Time $time)
    {
        $this->authorize('update', FilmSchedule::class);

        return view('admin.time.edit', compact('time'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TimeRequest $request
     * @param \App\Time $time
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TimeRequest $request, \App\Time $time)
    {
        $this->authorize('update', FilmSchedule::class);

        try {
            $result = $this->timeRepository->update(null, $time, $request->all());

            if ($result instanceof MessageBag) {
                return back()->withErrors($result)->withInput();
            }

            return redirect(route('fs.getShowtime', ['schedule' => $result->getSchedule()]))
                ->with('success', __('The [<code>:filmName</code>]\'s schedule have updated to [<code>:time</code>].', [
                    'time' => $result->getStartTime() . ' - ' . $result->getStopTime(),
                    'filmName' => $result->getSchedule()->getFilm()->getTitle()
                ]));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Time $time
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(\App\Time $time)
    {
        $this->authorize('delete', FilmSchedule::class);

        try {
            /** @var \App\Time $time */
            $time = $this->timeRepository->delete(null, $time);

            $message = __('The [<code>:time</code>] showtime have deleted.', [
                'time' => $time->getStartTime() . '-' . $time->getStopTime()
            ]);

            return request()->ajax() ?
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]) :
                redirect(route('fs.getShowtime', ['schedule' => $time->getSchedule()]))->with('success', $message);
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
     * @param FilmSchedule $schedule
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createTimeBySchedule(FilmSchedule $schedule)
    {
        $this->authorize('create', FilmSchedule::class);

        return view('admin.time.create_by_schedule', compact('schedule'));
    }

    /**
     * Mass destroy model
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massDestroy()
    {
        $this->authorize('delete', FilmSchedule::class);

        try {
            $times = request('times');

            $deletedItems = [];

            /** @var string $time */
            foreach ($times as $time) {
                /** @var \App\Time $deletedItem */
                $deletedItem = $this->timeRepository->delete($time, null);

                array_push($deletedItems, '<code>' . $deletedItem->getFormatStartTime() . '-' . $deletedItem->getFormatStopTime() .'</code>');
            }

            $message = __('The [:times] show times have deleted.', ['times' => implode(', ', $deletedItems)]);
            return !request()->ajax() ?
                redirect(route('fs.index'))
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
