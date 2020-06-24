<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class NoChangedException
 *
 * @package App\Exceptions
 */
class NoChangedException extends Exception
{
    public function render()
    {
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
