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

//    public function array()
//    {
//        $old = [
//            'name' => "Lục Thần Ca",
//            'email' => "lucthanca@gmail.com",
//            'address' => 'lai xá, kim chung',
//            'stk' => '123456',
//            'phone' => "0327811555",
//            'card' => [
//                'card_name' => 'student card',
//                'card_number' => '2233445566',
//                'test' => [
//                    'hihi' => 'haha',
//                    'hehe' => [
//                        'no' => 'yes'
//                    ]
//                ]
//            ]
//        ];
//        $new = [
//            'name' => "Lục Thần Ca",
//            'phone' => "0969397004",
//            'address' => null,
//            'stk' => '',
//            'dob' => "11/05/1998",
//            'card' => [
//                'card_name' => 'student card [add more]',
//                'card_number' => '2233445566',
//                'test' => [
//                    'hihi' => 'haha2',
//                    'hoho' => 'hehe'
//                ]
//            ]
//        ];
//
//        dd($this->arrayDiffRecursive($old, $new));
//    }
}
