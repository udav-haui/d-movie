<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

/**
 * Class NoChangedException
 *
 * @package App\Exceptions
 */
class NoChangedException extends Exception
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function render($request)
    {
        if (!$request->isJson()) {
            return back()->withWarning($this->getMessage())->withInput();
        }
        throw new HttpResponseException(
            response()->json(
                [
                    "message" => __("Nothing be changed!")
                ],
                304
            )
        );
    }
}
