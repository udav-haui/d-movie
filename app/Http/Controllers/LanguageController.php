<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

/**
 * Class LanguageController
 *
 * @package App\Http\Controllers
 */
class LanguageController extends Controller
{
    /**
     * Change app language
     *
     * @param string $lang
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(string $lang)
    {
        Session::put('locale', $lang);
        return redirect()->back();
    }
}
