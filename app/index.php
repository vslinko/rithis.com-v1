<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

if (is_readable(__DIR__ . '/config.php')) {
    foreach (include __DIR__ . '/config.php' as $key => $value) {
        $app[$key] = $value;
    }
}

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

$app->get('/', function () use ($app) {
    return $app['twig']->render('who-we-are.twig');
});

$app->run();
