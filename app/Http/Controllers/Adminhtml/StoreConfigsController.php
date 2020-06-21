<?php
namespace App\Http\Controllers\Adminhtml;

use App\Config;

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

    /**
     * Save payment methods configs
     */
    public function paymentMethods()
    {
        $section = [
            'parent' => 'sales',
            'active_child' => 'payment_methods'
        ];
        return view('admin.store.payment_methods', compact('section'));
    }

    /**
     * Get payment methods configs
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getPaymentMethods()
    {
        $this->authorize('salesPaymentMethods', Config::class);
        $responseData = [];

        $responseData['momo']['MOMO_PARTNER_CODE'] = env('MOMO_PARTNER_CODE') ?? null;
        $responseData['momo']['MOMO_ACCESS_KEY'] = env('MOMO_ACCESS_KEY') ?? null;
        $responseData['momo']['MOMO_SECRET_KEY'] = env('MOMO_SECRET_KEY') ? true : null;
        $responseData['momo']['MOMO_ENDPOINT'] = env('MOMO_ENDPOINT') ?? null;
        return response()->json(
            [
                'status' => 200,
                'data' => $responseData
            ]
        );
    }

    public function savePaymentMethods()
    {
        $momoData = request('input')['momo'] ?? null;
        if ($momoData) {
            if (is_bool($momoData['MOMO_SECRET_KEY'])) {
                unset($momoData['MOMO_SECRET_KEY']);
            }
            try {
                foreach ($momoData as $key => $value) {
                    setEnv($key, $value);
                }
            } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
                return response()->json(
                    [
                        'messages' => $e->getMessage()
                    ],
                    400
                );
            }
            return response()->json(
                [
                    'status' => 200,
                    'messages' => __("You saved configuration")
                ],
                200
            );
        }
        return response()->json(
            [
                'status' => 404,
                'messages' => __("Somethings went wrong!")
            ],
            404
        );
    }
}
