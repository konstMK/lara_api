<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(
    ['middleware' => 'jwt'],
    function() use ($router) {

        $router->get('/{city}', 'WeatherController@getWeather');
        $router->post('/weather', 'WeatherController@saveWeather');
        $router->delete('/{city}', 'WeatherController@deleteCity');

        $router->get('/film/{film}', 'FilmController@search');

    }
);

$router->post('/login', 'AuthController@login');
$router->post('/login/apikey', 'AuthController@loginApikey');
$router->post('/register', 'AuthController@register');