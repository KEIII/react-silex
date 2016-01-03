<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->get('/hello/', function (Request $request) use ($app) {
    $name = $app->escape($request->get('name'));
    $response = new Response(sprintf('Hello, %s!', $name));
    $response->headers->setCookie(new Cookie('silex_test', $name));
    $response->headers->setCookie(new Cookie('silex_time', time()));

    return $response;
});

$app->get('/favicon.ico', function () use ($app) {
    $filepath = __DIR__.'/favicon.ico';

    return new Response(file_get_contents($filepath), 200, [
        'content-type' => mime_content_type($filepath),
    ]);
});

return $app;
