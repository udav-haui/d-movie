<?php
namespace App\Http\Controllers\Adminhtml;

use App\Config;
use App\Exceptions\NoChangedException;
use App\Repositories\Interfaces\StoreConfigRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class StoreConfigsController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class StoreConfigsController extends \App\Http\Controllers\Controller
{
    /**
     * @var StoreConfigRepositoryInterface
     */
    private $configRepository;

    /**
     * StoreConfigsController constructor.
     *
     * @param StoreConfigRepositoryInterface $configRepository
     */
    public function __construct(
        StoreConfigRepositoryInterface $configRepository
    ) {
        $this->configRepository = $configRepository;
    }

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
     * @api
     */
    public function getPaymentMethods()
    {
        try {
            $this->authorize('salesPaymentMethods', Config::class);
            /** @var Config $paymentConfig */
            $paymentConfig = $this->configRepository
                ->getFilter(null, [Config::SECTION_ID => Config::SALES_PAYMENT_METHOD_SECTION_ID])
                ->first();
            $responseData = [];
            if ($paymentConfig) {
                $paymentConfigVal = $paymentConfig->getConfigValues();
                $momoPaymentConfigVal = $paymentConfigVal['momo'] ?? null;
                $momoData['partner_code'] = $momoPaymentConfigVal['partner_code'] ?? null;
                $momoData['access_key'] = $momoPaymentConfigVal['access_key'] ?? null;
                $momoData['end_point'] = $momoPaymentConfigVal['end_point'] ?? null;
                $secretKeyVal = $momoPaymentConfigVal['secret_key'] ?? null;
                $momoData['secret_key'] =  $secretKeyVal ? true : null;
                $responseData['momo'] = $momoData;
            }
            return response()->json(
                [
                    'status' => 200,
                    'data' => $responseData
                ]
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            throw new HttpResponseException(
                response()->json(["message" => __("We can't let you see this content")], 403)
            );
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json(['message' => __($e->getMessage())], 400));
        }
    }

    /**
     * Save payment method configs
     *
     * @api
     * @return \Illuminate\Http\JsonResponse
     * @throws NoChangedException
     */
    public function savePaymentMethods()
    {
        try {
            $this->authorize('salesPaymentMethods', Config::class);
            $paymentConfigs = request('input');

            $configQuery = $this->configRepository->getFilter(
                null,
                [Config::SECTION_ID => Config::SALES_PAYMENT_METHOD_SECTION_ID]
            );
            /** @var Config $config */
            $config = $configQuery->first();
            if ($paymentConfigs) {
                if ($paymentConfigs['momo']) {
                    if (is_bool($paymentConfigs['momo']['secret_key'])) {
                        unset($paymentConfigs['momo']['secret_key']);
                        if ($config && ($configDataArray = $config->getConfigValues())) {
                            if (isset($configDataArray['momo'])) {
                                $oldSecretData = $configDataArray['momo']['secret_key'] ?? null;
                                $paymentConfigs['momo']['secret_key'] = $oldSecretData;
                            }
                        }
                    }
                }
            }
            if ($config) {
                $this->configRepository->update(
                    null,
                    $config,
                    [Config::CONFIG_VALUES => $paymentConfigs],
                    true,
                    false
                );
            } else {
                $this->configRepository->create([
                    Config::SECTION_ID => Config::SALES_PAYMENT_METHOD_SECTION_ID,
                    Config::CONFIG_VALUES => $paymentConfigs
                ], false);
            }
            return response()->json(
                [
                    'status' => 200,
                    'message' => __("You saved configuration")
                ],
                200
            );
        } catch (NoChangedException $e) {
            throw $e;
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            throw new HttpResponseException(
                response()->json(["message" => __($e->getMessage())], 403)
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ],
                400
            );
        }

        /** edit env file
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
        );*/
    }
}
