<?php
namespace App\Http\Controllers\Adminhtml;

/**
 * Class StoreConfigsController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class StoreConfigsController extends \App\Http\Controllers\Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.store.config_index');
    }
}
