<?php

/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 24/03/16
 * Time: 10:19
 */

namespace App\Controller;


use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;


use App\Model\TypeCommercantModel;


class TypeCommercantController implements  ControllerProviderInterface
{
    private $typeCommercantModel;


    public function show(Application $app){

        $this->typeCommercantModel = new TypeCommercantModel($app);

        $types = $this->typeCommercantModel->getAllTypeCommercants();

        return $app["twig"]->render("typeCommercant/v_listeAll.twig",array('typesCommercants'=>$types, 'page' => 'liste2', 'path' => PATH, 'titre' => 'Liste des commerçants', '_SESSION' => $_SESSION));
    }


    public function connect(Application $app)
    {
        $index = $app['controllers_factory'];
        $index->get("/", 'App\Controller\TypeCommercantController::show');
        $index->get("/listeTypeCommercant", 'App\Controller\TypeCommercantController::show');
        return $index;

    }
}