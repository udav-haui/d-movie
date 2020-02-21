<?php

namespace App\Http\Controllers\Adminhtml;

use App\FilmSchedule;
use App\Http\Requests\ScheduleRequest;
use App\Repositories\Interfaces\FilmScheduleRepositoryInterface;
use App\Time;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

/**
 * Class FilmScheduleController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class FilmScheduleController extends \App\Http\Controllers\Controller
{

    /**
     * @var FilmScheduleRepositoryInterface
     */
    private $scheduleRepository;

    /**
     * FilmScheduleController constructor.
     * @param FilmScheduleRepositoryInterface $scheduleRepository
     */
    public function __construct(
        FilmScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', FilmSchedule::class);

        try {
            $schedules = $this->scheduleRepository->all(['times', 'show', 'film']);

            $authUser = auth()->user();

            if (request()->ajax()) {
                $dt = datatables()->of($schedules);



                $dt->editColumn('status', function (FilmSchedule $schedule) use ($authUser) {
                    $htmlRaw = "<div class=\"dmovie-flex-container\">";
                    $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                    $htmlRaw .= "<input type=\"checkbox\"";
                    $htmlRaw .= (int)$schedule->getStatus() === FilmSchedule::ENABLE ? "checked " : "";
                    $htmlRaw .= "class=\"status-checkbox\"".
                        "value=\"{$schedule->getId()}\"".
                        "data-id=\"{$schedule->getId()}\"";
                    if ($authUser->cant('update', FilmSchedule::class)) {
                        $htmlRaw .= "disabled";
                    }
                    $htmlRaw .= " dmovie-switch-dt/>";
                    $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$schedule->getStatusLabel()}
                          </label>
                    </div>
                </div></div>";

                    return $htmlRaw;
                });

                $dt->addColumn('times', function (FilmSchedule $schedule) {
                    $htmlRaw = "<a href=\"" . route('fs.getShowtime', ['schedule' => $schedule->getId()]) . "\"
                                   type=\"button\" class=\"";

                    $htmlRaw .= "col-md-12 btn dmovie-btn dmovie-btn-success\"";
                    $htmlRaw .= "title=\"" . __('View showtime') . "\">";
                    $htmlRaw .= "<i class=\"mdi mdi-clock-start\"></i></a>";
                    return $htmlRaw;
                });


                if ($authUser->can('canEditDelete', FilmSchedule::class)) {
                    $htmlRaw = "";
                    $dt->addColumn('task', function (FilmSchedule $schedule) use ($authUser, $htmlRaw) {
                        if ($authUser->can('update', FilmSchedule::class)) {
                            $htmlRaw .= "<a href=\"" . route('fs.edit', ['f' => $schedule->getId()]) . "\"
                                   type=\"button\" class=\"";
                            if ($authUser->cant('delete', FilmSchedule::class)) {
                                $htmlRaw .= "col-md-12 ";
                            } else {
                                $htmlRaw .= 'col-md-6 ';
                            }

                            $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                            $htmlRaw .= "title=\"" . __('Detail') . "\">";
                            $htmlRaw .= "<i class=\"fa fa-pencil-square-o\"></i></a>";
                        }

                        if ($authUser->can('delete', FilmSchedule::class)) {
                            $cssClass = $authUser->can('update', FilmSchedule::class) ? "col-md-6" : "col-md-12";

                            $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
                                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\" " . __('Delete') . " \"
                                            data-id=\"{$schedule->getId()}\"
                                            url=\"" . route('fs.destroy', ['f' => $schedule->getId()]) . "\">
                                        <i class=\"fa fa-trash-o\"></i>
                                    </button>";
                        }

                        return $htmlRaw;
                    });
                } else {
                    $dt->addColumn('task', '');
                }


                return $dt->rawColumns(
                    ['status', 'task', 'times']
                )->make();

            }

            return view('admin.schedule.index', compact('schedules'));
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', FilmSchedule::class);

        return view('admin.schedule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ScheduleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ScheduleRequest $request)
    {
        $this->authorize('create', FilmSchedule::class);

        try {
            /** @var FilmSchedule $schedule */
            $schedule = $this->scheduleRepository->create($request->all());

            if ($schedule instanceof MessageBag) {
                return back()->withErrors($schedule)->withInput();
            }

            return redirect(route('fs.index'))->with(
                'success',
                __(
                    'The schedule for [:filmName] have created.',
                    ['filmName' => $schedule->getFilm()->getTitle()]
                )
            );

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FilmSchedule  $filmSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(FilmSchedule $filmSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FilmSchedule  $filmSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(FilmSchedule $filmSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FilmSchedule  $filmSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FilmSchedule $filmSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FilmSchedule $f
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(FilmSchedule $f)
    {
        $this->authorize('delete', FilmSchedule::class);

        try {
            /** @var FilmSchedule $schedule */
            $schedule = $this->scheduleRepository->delete(null, $f);

            $message = __('The schedule [:film] on [:startDate] have deleted.', [
                'film' => $schedule->getFilm()->getTitle(),
                'startDate' => $schedule->getStartDate()
            ]);
            return request()->ajax() ?
                response()->json([
                    'status' => 200,
                    'message' =>$message
                ]) :
                redirect()->route('fs.index')->with('success', $message);
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return request()->ajax() ?
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]) :
                back()->with('error', $message)->withInput();
        }
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
            $schedules = request('schedules');

            $deletedItems = [];

            /** @var string $schedule */
            foreach ($schedules as $schedule) {
                /** @var FilmSchedule $deletedItem */
                $deletedItem = $this->scheduleRepository->delete($schedule, null);

                array_push($deletedItems, $deletedItem->getFilm()->getTitle());
            }

            $message = __('The schedule of [:schedules] have deleted.', ['schedules' => implode(', ', $deletedItems)]);
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

    /**
     * Multi update
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massUpdate()
    {
        $this->authorize('update', FilmSchedule::class);

        try {
            $updatedItems = [];

            $schedules = request('schedules');
            $fields = request('fields');

            foreach ($schedules as $schedule) {
                /** @var FilmSchedule $updatedItem */
                $updatedItem = $this->scheduleRepository->update($schedule, null, $fields);
                if (!$updatedItem) {
                    throw new \Exception(__('We cant update the schedule have id [:id]', ['id' => $schedule]));
                }
                array_push($updatedItems, $updatedItem->getFilm()->getTitle());
            }

            $message = __('The schedule of [:schedules] have updated.', ['schedules' => implode(', ', $updatedItems)]);
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

    /**
     * Get show time list
     *
     * @param FilmSchedule $schedule
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getShowtime(FilmSchedule $schedule)
    {
        $this->authorize('view', FilmSchedule::class);

        try {
            if (request()->ajax()) {
                $authUser = auth()->user();

                $times = $schedule->getTimes();

                $dt = datatables()->of($times);


                if ($authUser->can('canEditDelete', FilmSchedule::class)) {
                    $htmlRaw = "";
                    $dt->addColumn('task', function (Time $time) use ($authUser, $htmlRaw) {
                        if ($authUser->can('update', FilmSchedule::class)) {
                            $htmlRaw .= "<a href=\"" . route('times.edit', ['time' => $time->getId()]) . "\"
                                   type=\"button\" class=\"";
                            if ($authUser->cant('delete', FilmSchedule::class)) {
                                $htmlRaw .= "col-md-12 ";
                            } else {
                                $htmlRaw .= 'col-md-6 ';
                            }

                            $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                            $htmlRaw .= "title=\"" . __('Detail') . "\">";
                            $htmlRaw .= "<i class=\"fa fa-eye\"></i></a>";
                        }

                        if ($authUser->can('delete', FilmSchedule::class)) {
                            $cssClass = $authUser->can('update', FilmSchedule::class) ? "col-md-6" : "col-md-12";

                            $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
                                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\" " . __('Delete') . " \"
                                            data-id=\"{$time->getId()}\"
                                            url=\"" . route('times.destroy', ['time' => $time->getId()]) . "\">
                                        <i class=\"fa fa-trash-o\"></i>
                                    </button>";
                        }

                        return $htmlRaw;
                    });
                } else {
                    $dt->addColumn('task', '');
                }


                return $dt->rawColumns(['task'])->make();
            }

            return view('admin.time.index_by_schedule', compact('schedule'));
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return !request()->ajax() ?
                redirect(route('fs.getShowtime', ['schedule' => $schedule]))
                    ->with(
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
