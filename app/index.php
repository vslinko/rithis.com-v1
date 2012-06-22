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
})->bind('who_we_are');

$app->get('/tags', function () use ($app) {
    $tags = array(
        'Серверная разработка' => array(
            'PHP 5.3+' => 'http://php.net',
            'Symfony2' => 'http://symfony.com',
            'Doctrine ORM/ODM/DBAL' => 'http://www.doctrine-project.org',
            'Silex' => 'http://silex.sensiolabs.org',
            'Twig' => 'http://twig.sensiolabs.org',
            'Composer' => 'http://getcomposer.org',
            'PHPUnit' => 'https://github.com/sebastianbergmann/phpunit',
            'JavaScript' => 'http://www.ecmascript.org',
            'CoffeeScript' => 'http://coffeescript.org',
            'Git' => 'http://git-scm.com',
        ),
        'Клиентская разработка' => array(
            'jQuery' => 'http://jquery.com',
            'LESS' => 'http://lesscss.org',
            'Bootstrap' => 'http://twitter.github.com/bootstrap',
            'HTML5' => 'http://www.w3.org/TR/html5/',
            'CSS3' => 'http://www.w3.org/Style/CSS/',
        ),
        'Системное администрирование' => array(
            'NGINX' => 'http://nginx.com',
            'Ubuntu' => 'http://www.ubuntu.com',
            'KVM' => 'http://www.linux-kvm.org/page/Main_Page',
            'AWS' => 'http://aws.amazon.com',
        ),
        'Базы данных' => array(
            'MySQL' => 'http://www.mysql.com',
            'MongoDB' => 'http://www.mongodb.org',
            'Memcached' => 'http://memcached.org',
        ),
        'Continuous Integration и Deployment' => array(
            'Travis CI' => 'http://travis-ci.org',
            'capifony' => 'http://capifony.org',
            'Phing' => 'http://www.phing.info/trac/',
        ),
        'Управление проектами' => array(
            'Basecamp' => 'http://basecamp.com',
            'Kayako Fusion' => 'http://www.kayako.com/products/fusion',
            'GitHub' => 'http://github.com',
        ),
        'Сертификации' => array(
            'Zend Certified Engineer PHP 5.3' => 'http://www.zend.com/en/yellow-pages#show-ClientCandidateID=ZEND017112',
        ),
    );

    return $app['twig']->render('our-tags.twig', array('tags' => $tags));
})->bind('our_tags');

$app->run();
