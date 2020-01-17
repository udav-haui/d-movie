<?php

namespace App\Http\Controllers\Adminhtml\Auth;

use App\Helper\Data;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Log;
use App\Services\UserService;
use App\User;
use Carbon\Carbon;
use Cache;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index()
    {
        return redirect('/admin');
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
     * @param  int  $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        /**
         * Make instance from namespace string
         *
         * $log = Log::find(3); $target = $log->target; $instance = new $target();
         */
//        $log = Log::find(1);
//        $target = $log->target_model;
//        $instance = new $target();
//        $array = json_decode($log->message, true);
//        $u = new User();
//        $u->fill($array);
//        dd($u->id, $array, $instance->find($log->target_id) ,$log->message);
        return view('admin.auth.profile', compact('user'));
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
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserRequest $request, User $user)
    {
        // $this->authorize('update', $user);
        try {
            $this->userService->update($request, $user);
        } catch (AuthorizationException $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->back();
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
