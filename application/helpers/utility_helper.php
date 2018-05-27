<?php
if (!function_exists('getLatLng')) {
    function getLatLng($address)
    {
        if (is_array($address)) {
            $address = $address['address_line_1'] . ' ' . $address['address_line_2'] . ' ' . $address['state'] . ' ' . $address['city'] . ' ' . $address['zip'];
        }
        $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false');
        $geo = isJson($geo);
        if (!$geo) return ['latitude' => 00.000000, 'longitude' => 00.000000];

        if (!empty($geo['status']) && strtolower($geo['status']) !== strtolower('OK')) return ['latitude' => 00.000000, 'longitude' => 00.000000];
        $latitude = $geo['results'][0]['geometry']['location']['lat'];
        $longitude = $geo['results'][0]['geometry']['location']['lng'];
        $formatted_address = $geo['results'][0]['formatted_address'];

        return ['latitude' => $latitude, 'longitude' => $longitude, 'formatted_address' => $formatted_address];
    }
}

if (!function_exists('request')) {
    function request()
    {
        return new \MYClasses\Http\Request();
    }
}

if (!function_exists('response')) {
    function response()
    {
        return new \MYClasses\Http\Response();
    }
}

if (!function_exists('aes')) {
    function aes()
    {
        return new \MYClasses\Providers\AESProvider();
    }
}

if (!function_exists('validateLatitude')) {
    function validateLatitude($lat)
    {
        return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat);
    }
}

if (!function_exists('validateLongitude')) {
    function validateLongitude($long)
    {
        return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $long);
    }
}