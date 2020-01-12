<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        return $this->middleware('auth');
    }

    /**
     * Show dashboard view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /**
         * After access to admin page will put a prefix to session
         */
        Session::put('prefix', request()->route()->getPrefix());
        return view('admin.dashboard');
    }
}
