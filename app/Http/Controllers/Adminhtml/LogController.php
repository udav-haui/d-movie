<?php

namespace App\Http\Controllers\Adminhtml;

use App\Log;
use Illuminate\Http\Request;

/**
 * Class LogController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class LogController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Log::class);

        if (request()->ajax()) {
            $logs = Log::with('user')->get();

            $authUser = auth()->user();
            $dt = datatables()->of($logs);

            $dt->editColumn(Log::CREATED_AT, function (Log $log) {
                return get_format_day_date_string($log->created_at, "Do MMMM YYYY");
            });

            $dt->editColumn(Log::USER, function (Log $log) use ($authUser) {
                if ($authUser->can('update', \App\User::class)) {
                    return '<a target="_blank" href="'.route('users.edit', ['user' => $log->getUser()]).'">'.
                        '<d-mark-1>ID: '.$log->getUser()->getId().'-'.
                        $log->getUser()->getName() ?? __('Unnamed').
                        '</d-mark-1>'.
                        '</a>';
                } else {
                    return '<d-mark-1>ID: '.$log->getUser()->getName() ?? __('Unnamed').'</d-mark-1>';
                }
            });


            $dt->editColumn(Log::ACTION, function (Log $log) use ($authUser) {
                $mark = '';

                if ($log->getAction() === Log::CREATE) {
                    $mark = '<d-mark-create>'.__(Log::CREATE).'</d-mark-create>';
                } elseif ($log->getAction() === Log::UPDATE) {
                    $mark = '<d-mark-update>'.__(Log::UPDATE).'</d-mark-update>';
                } elseif ($log->getAction() === Log::DELETE) {
                    $mark = '<d-mark-delete>'.__(Log::DELETE).'</d-mark-delete>';
                } else {
                    $mark = '<d-mark-model>'.__($log->getAction()).'</d-mark-model>';
                }

                $model = $log->getTargetModel();
                $model = new $model();
                return __(
                    'The user has :mark the model <d-mark-model>:model</d-mark-model> with ID = <code>:id</code>',
                    [
                        'mark' => $mark,
                        'model' => $model::getModelName(),
                        'id' => $log->getTargetId()
                    ]
                );
            });

            $htmlRaw = "";
            $dt->addColumn('task', function (Log $log) use ($htmlRaw) {
                $htmlRaw .= "<a href=\"" . route('logs.show', ['log' => $log->getId()]) . "\"
                                   type=\"button\" class=\"";

                $htmlRaw .= "col-md-12 col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                $htmlRaw .= "title=\"" . __('Detail') . "\">";
                $htmlRaw .= "<i class=\"fa fa-eye\"></i></a>";

                return $htmlRaw;
            });

            return $dt->rawColumns(['task', Log::USER, Log::ACTION])->make();
        }

        return view('admin.log.index');
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
     * @param Log $log
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Log $log)
    {
        $this->authorize('view', Log::class);

        return view('admin.log.view', compact('log'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
