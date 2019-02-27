<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WeatherController extends Controller
{
    private $citiesWeather = [
        'dnipro' => [
            'temp' => 23.4,
            'humidity' => 56,
            'wind_speed' => 5.3
        ],
        'kiev' => [
            'temp' => 22.4,
            'humidity' => 76,
            'wind_speed' => 23.3
        ]
    ];

    public function getWeather($city)
    {
        return $this->isCityExists($city);
        return response()->json($this->citiesWeather[$city]);
    }

    public function saveWeather(Request $request)
    {
        $contentType = $request->header('content-type');
        if ($contentType == 'application/json') {
            return response()->json('asjkdbvasodc');
        } elseif ($contentType === 'application/xml') {
            return response('<message>error</message>', 200, ['Content-type' => 'application/xml']);
        }
        $city = $request->get('city');

        if (isset($this->citiesWeather[$city])) {
            return response()->json(
                ['error' => [
                    'message' => 'City already exists'
                ]], Response::HTTP_CONFLICT
            );
        }

        $data = $request->all();
        return response(null, Response::HTTP_CREATED, [
            'Location' => '/'. $city
        ]);
    }

    public function deleteCity($city)
    {
        if (!isset($this->citiesWeather[$city])) {
            return response()->json(
                [
                    'error' => [
                        'message' => 'City not found',
                        'error_code' => '100102'
                    ]
                ], Response::HTTP_NOT_FOUND
            );
        }
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $city
     * @return \Illuminate\Http\JsonResponse
     */
    private function isCityExists($city)
    {

    }
}