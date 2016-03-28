<?php

/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 24/03/16
 * Time: 10:19
 */

namespace App\Controller;


use Silex\Application;
use Silex\ControllerProviderInterface;

use App\Model\TypeCommercantModel;


class TypeCommercantController implements  ControllerProviderInterface
{
    private $typeCommercantModel;


    public function show(Application $app){

        $this->typeCommercantModel = new TypeCommercantModel($app);

        $types = $this->typeCommercantModel->getAllTypeCommercants();

        return $app["twig"]->render("typeCommercant/v_listeAll.twig",array('typesCommercants'=>$types, 'page' => 'liste2', 'path' => PATH, 'titre' => 'Liste des commerçants', '_SESSION' => $_SESSION));
    }

    public function renderFormSupprimer(Application $app, $id){
        $this->typeCommercantModel = new TypeCommercantModel($app);
        $type = $this->typeCommercantModel->getType($id);

        return $app['twig']->render('typeCommercant/v_SupprimerType.twig', array('type_commercant'=> $type, 'path' => PATH, 'titre' => 'Suppression d\'un type', '_SESSION' => $_SESSION));
    }

    public function validFormSupprimer(Application $app, $id){
        $this->typeCommercantModel = new TypeCommercantModel($app);
        $this->typeCommercantModel->removeType($id);

        return $app->redirect($app["url_generator"]->generate('typesCommercants.liste'));
    }

    public function renderFormEditer(Application $app, $id){
        $this->typeCommercantModel = new TypeCommercantModel($app);
        $type = $this->typeCommercantModel->getType($id);

        return $app['twig']->render('typeCommercant/v_EditerType.twig', array('type'=> $type, 'path' => PATH, 'titre' => 'Edition d\'un type', '_SESSION' => $_SESSION));
    }

    public function validFormEditer(Application $app, $id){
        $this->typeCommercantModel = new TypeCommercantModel($app);
        $this->typeCommercantModel->editType($id, $_POST);

        return $app->redirect($app["url_generator"]->generate('typesCommercants.liste'));
    }

    public function renderFormCreer(Application $app){
        return $app['twig']->render('typeCommercant/v_CreerType.twig', array('path' => PATH, 'titre' => 'Création d\'un type', '_SESSION' => $_SESSION));
    }

    public function validFormCreer(Application $app){
        $this->typeCommercantModel = new TypeCommercantModel($app);

        $donnees['noms'] = $_POST['noms'];

        $this->typeCommercantModel->addType($donnees);

        return $app->redirect($app["url_generator"]->generate('typesCommercants.liste'));
    }

    public function connect(Application $app)
    {
        $index = $app['controllers_factory'];
        $index->get("/", 'App\Controller\TypeCommercantController::show')->bind('typesCommercants.liste');
        $index->get("/listeTypeCommercant", 'App\Controller\TypeCommercantController::show');

        $index->get("/supprimerTypeCommercant/{id}", 'App\Controller\TypeCommercantController::renderFormSupprimer')->bind('typesCommercants.supprimer');
        $index->delete("/supprimerTypeCommercant/{id}", 'App\Controller\TypeCommercantController::validFormSupprimer');

        $index->get('/editerTypeCommercant/{id}', 'App\Controller\TypeCommercantController::renderFormEditer')->bind('typesCommercants.editer');
        $index->put('/editerTypeCommercant/{id}', 'App\Controller\TypeCommercantController::validFormEditer');

        $index->get('/creerTypeCommercant', 'App\Controller\TypeCommercantController::renderFormCreer')->bind('typesCommercants.creer');
        $index->post('/creerTypeCommercant', 'App\Controller\TypeCommercantController::validFormCreer');
        return $index;

    }
}