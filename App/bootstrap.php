<?php
include('config.php');

$loader = require_once __DIR__.'/../vendor/autoload.php';
$loader->add("App",dirname(__DIR__));
$loader->addPsr4('App\\',__DIR__);

$app = new Silex\Application();
$app['debug'] = true;


$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => hostname,
        'host' => hostname,
        'dbname' => database,
        'user' => username,
        'password' => password,
        'charset'   => 'utf8mb4',
    ),
));


$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// utilisation des sessoins
$app->register(new Silex\Provider\SessionServiceProvider());



$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => join(DIRECTORY_SEPARATOR, array(__DIR__, 'View'))
));

$app['twig']->addFunction('viewDate', new Twig_Function_Function('App\Helper\DateHelper::transformerToFrance'));


$app->mount('/', new App\Controller\IndexController());
$app->mount('/typeCommercant', new App\Controller\TypeCommercantController($app));
$app->mount('/commercant', new App\Controller\CommercantController($app));
$app->mount('/user', new App\Controller\UserController($app));

use Symfony\Component\HttpFoundation\Request;
Request::enableHttpMethodParameterOverride();

$app->run();