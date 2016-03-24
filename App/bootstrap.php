<?php
include('config.php');

$loader = require_once __DIR__.'/../vendor/autoload.php';
$loader->add("App",dirname(__DIR__));
$loader->addPsr4('App\\',__DIR__);

$app = new Silex\Application();
$app['debug'] = true;


$app->get('/hello/{name}', function ($name) use ($app) {
  return 'Hello '.$app->escape($name);
});

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
    "twig.path" => dirname(__DIR__) . "/App/View"));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => join(DIRECTORY_SEPARATOR, array(__DIR__, 'View'))
));

$app->mount('/typeCommercant', new App\Controller\TypeCommercantController($app));
$app->mount("/commercant", new App\Controller\CommercantController($app));
$app->mount("/", new App\Controller\IndexController());

use Symfony\Component\HttpFoundation\Request;
Request::enableHttpMethodParameterOverride();

$app->run();