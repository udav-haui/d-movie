<?php

namespace App\Http\Controllers;

/**
 * Trait ReturnTypeTrait
 *
 * @package App\Http\Controllers
 */
trait ReturnTypeTrait
{
    /**
     * @param null $message
     * @param null $url
     * @param null $exception
     * @param string $type
     * @param null $data
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function return($message = null, $url = null, $exception = null, $type = 'success', $data = null)
    {
        if ($exception !== null) {
            $message = $exception->getMessage();
        }
        $status = $type === 'success' ? 200 : 404;
        return !request()->ajax() ?
            redirect($url)
                ->with($type, $message) :
            response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data
            ]);
    }
}
