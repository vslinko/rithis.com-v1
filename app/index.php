<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

if (is_readable(__DIR__ . '/config.php') && is_array($config = include __DIR__ . '/config.php')) {
    foreach ($config as $key => $value) {
        $app[$key] = $value;
    }
}

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new \Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(
        'messages' => array(
            'ru' => require __DIR__ . '/translations/ru.php',
            'en' => require __DIR__ . '/translations/en.php',
        ),
    ),
));

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());

$app->mount('/{_locale}', new Rithis\ControllerProvider());

$app->get('/', function () use ($app) {
    $locale = stripos($app['request']->server->get('HTTP_ACCEPT_LANGUAGE'), 'ru') === false ? 'en' : 'ru';

    return $app->redirect($app['url_generator']->generate('who_we_are', array(
        '_locale' => $locale,
    )));
});

if (isset($config) && is_array($config)) {
    foreach ($config as $key => $value) {
        $app[$key] = $value;
    }
}

$app['session']->start();
$app->run();
