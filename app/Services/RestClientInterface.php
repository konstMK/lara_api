<?php

namespace App\Services;


interface RestClientInterface
{
    public function get($url);
    public function post(array $data);
    public function delete($url);
    public function put($id, array $data);
}