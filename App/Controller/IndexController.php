<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;


class IndexController implements ControllerProviderInterface
{

    public function index(Application $app)
    {
        return $app["twig"]->render("v_base.twig", array('page' => 'index', 'titre' => 'DONT DO THIS', 'path'=> PATH,'_SESSION'=>$_SESSION));
    }

    public function info()
    {
        return phpinfo();
    }

    public function connect(Application $app)
    {
        // créer un nouveau controleur basé sur la route par défaut
        $index = $app['controllers_factory'];
        $index->match("/", 'App\Controller\IndexController::index');
        $index->match("/index", 'App\Controller\IndexController::index');
        $index->match("/info", 'App\Controller\IndexController::info');

        return $index;
    }
}
