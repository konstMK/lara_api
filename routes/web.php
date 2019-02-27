<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('/{city}', 'WeatherController@getWeather');
$router->post('/weather', 'WeatherController@saveWeather');
$router->delete('/{city}', 'WeatherController@deleteCity');