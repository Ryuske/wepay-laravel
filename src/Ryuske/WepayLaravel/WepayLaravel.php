<?php

namespace Ryuske\WepayLaravel;

class WepayLaravel {

    /**
     * Tell the WePay API if we're using staging or production values
     */
    public function __construct() {
        // if not production && useStaging then use stage
        // else if not production && not useStaging use production
        // else use production

        if (!\App::environment('production')) {
            if (\Config::get('wepay-laravel::useStaging')) {
                \WePay::useStaging(\Config::get('wepay-laravel::staging.client_id'), \Config::get('wepay-laravel::staging.client_secret'));
            } else {
                \WePay::useProduction(\Config::get('wepay-laravel::production.client_id'), \Config::get('wepay-laravel::production.client_secret'));
            }
        } else {
            \WePay::useProduction(\Config::get('wepay-laravel::production.client_id'), \Config::get('wepay-laravel::production.client_secret'));
        }
    }

    /**
     * Return the given configuration value
     *
     * @param $key
     * @return mixed
     */
    public function get($key) {
        if (!\App::environment('production')) {
            if (\Config::get('wepay-laravel::useStaging')) {
                return \Config::get('wepay-laravel::staging.' . $key);
            }
        }

        return \Config::get('wepay-laravel::production.' . $key);
    }

    /**
     * Used to make an API call
     *
     * @param $access_token
     * @param $endpoint
     * @param $data
     * @return \StdClass
     */
    public function request($access_token, $endpoint, $data) {
        try {
            $wepay_object = new \WePay($access_token);

            return $wepay_object->request($endpoint, $data);

        } catch (\WePayRequestException $e) {
            return NULL;
        }
    }
}