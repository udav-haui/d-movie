<?php


namespace App\Validators;

use GuzzleHttp\Client;

/**
 * Class ReCaptcha
 *
 * @package App\Validators
 */
class ReCaptcha
{
    /**
     * @param $attribute
     * @param string $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateCaptcha($attribute, $value, $parameters, $validator)
    {
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=>
                [
                    'secret'=>env('NOCAPTCHA_SECRET'),
                    'response'=>$value
                ]
            ]
        );
        $body = json_decode((string)$response->getBody());
        return $body->success;
    }
}
