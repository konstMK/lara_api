<?php

namespace App\Http\Controllers;


use App\Services\RestClientInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WeatherController extends Controller
{
    private $restClient;

    private const KELVIN = 273.15;

    public function __construct(RestClientInterface $restClient)
    {
        $this->restClient = $restClient;
    }

    public function getWeather($city)
    {
        $weather = $this->restClient
            ->get('api.openweathermap.org/data/2.5/weather?q='.$city
            .'&apikey=07d3021dbdefff9a6d1a21743e7ce8ac');

        $currentTemp = $weather['main']['temp'] - self::KELVIN;
        $pressure = $weather['main']['pressure'];
        $windSpeed = $weather['wind']['speed'];

        $result = [
            'temperature' => $currentTemp,
            'pressure' => $pressure,
            'wind_speed' => $windSpeed
        ];

        return response()->json($result);
    }
}