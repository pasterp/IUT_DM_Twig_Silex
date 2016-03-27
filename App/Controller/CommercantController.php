<?php

/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 24/03/16
 * Time: 09:13
 */

namespace App\Controller;


use App\Model\TypeCommercantModel;
use Silex\Application;
use Silex\ControllerProviderInterface;

use App\Model\CommercantModel;
use App\Helper\DateHelper;


//Token contrant les CSRF sur la suppression

class CommercantController implements ControllerProviderInterface
{
    private $commercantModel;
    private $typeCommercantModel;

    public function index(Application $app)
    {
        return $app["twig"]->render("v_base.twig", array('page' => 'index', 'titre' => 'Accueil Commerçant', 'path'=> PATH,'_SESSION'=>$_SESSION));
    }

    public function show(Application $app) {
        $this->commercantModel = new CommercantModel($app);


        $commercants = $this->commercantModel->getAllCommercants();

        return $app["twig"]->render("commercant/v_listeAll.twig",array('commercants'=>$commercants, 'page' => 'liste', 'path' => PATH, 'titre' => 'Liste des commerçants', '_SESSION' => $_SESSION));
    }

    public function formCreerCommercant(Application $app){
        $this->typeCommercantModel = new TypeCommercantModel($app);
        $types = $this->typeCommercantModel->getAllTypeCommercants();

        return $app["twig"]->render("commercant/v_CreerCommercant.twig", array('page' => 'ajout', 'path' => PATH, 'titre' => 'Liste des commerçants', '_SESSION' => $_SESSION, 'types' => $types));
    }

    public function creerCommercant(Application $app){
        if($app['session']->get('droit') != 'admin'){
            return $this->manqueDeDroits($app);
        }

        $erreurs = array();
        $donnees['nom'] = $_POST['nom'];
        if((! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom']))){
            $erreurs['nom']="Nom invalide";
        }
        $donnees['id_type_commercant'] = htmlspecialchars($_POST['type']);
        if(!is_numeric($donnees['id_type_commercant'])){
            $erreurs['id_type_commercant'] = "Le type n'est pas valide";
        }
        $donnees['prix_location'] = htmlspecialchars($_POST['tarif']);
        if(!is_numeric($donnees['prix_location'])){
            $erreurs['prix_location'] = "Le prix doit être numérique";
        }
        $donnees['date_installation'] = $_POST['date_installation'];
        if(!DateHelper::verifierDate($donnees['date_installation'])){
            $erreurs['date_installation'] = 'La date n\'a pas un format correct !';
        }else{
            $donnees['date_installation'] = DateHelper::transformerToAmerique($donnees['date_installation']);
        }

        if(!empty($erreurs)){
            $this->typeCommercantModel = new TypeCommercantModel($app);
            $typeProduits = $this->typeCommercantModel->getAllTypeCommercants();
            return $app["twig"]->render('commercant/v_CreerCommercant.twig', array('types' => $typeProduits, 'donnees' => $donnees, 'titre'=>"Commercants - Insertion d'un commerçant", 'page' => 'Ajout', 'path' => PATH, 'erreurs' => $erreurs));
        }else{
            $this->commercantModel = new CommercantModel($app);
            $this->commercantModel->insertCommercant($donnees);

            return $app->redirect($app["url_generator"]->generate('commercant.liste'));
        }

    }

    public function formSupprimerCommercant(Application $app, $id){
        $this->commercantModel = new CommercantModel($app);
        $commercant = $this->commercantModel->getCommercant($id);

        $csrf = base64_encode(random_bytes(10));
        $app['session']->set('csrf', $csrf);

        return $app["twig"]->render("commercant/v_SupprimerCommercant.twig", array('csrf' => $csrf, 'commercant' => $commercant, 'page' => 'other', 'path' => PATH, 'titre' => 'Suppresion d\'un commerçant', '_SESSION' => $_SESSION));
    }

    public function supprimerCommercant(Application $app, $id){
        if($app['session']->get('droit') != 'admin'){
            return $this->manqueDeDroits($app);
        }
        if(!hash_equals($_POST['_token'], $app['session']->get('csrf'))){
            return $this->requeteNonAutorisee($app);
        }

        $this->commercantModel = new CommercantModel($app);

        $this->commercantModel->removeCommercant($id);
        return $app->redirect(PATH.'index.php/commercant/listeCommercant');
    }

    public function formEditerCommercant(Application $app, $id){
        $this->commercantModel = new CommercantModel($app);
        $commercant = $this->commercantModel->getCommercant($id);

        $this->typeCommercantModel = new TypeCommercantModel($app);
        $types = $this->typeCommercantModel->getAllTypeCommercants();

        return $app["twig"]->render("commercant/v_EditerCommercant.twig", array('types' => $types, 'commercant'=>$commercant, 'page' => 'edit', 'path' => PATH, 'titre' => 'Edition d\'un commerçant', '_SESSION' => $_SESSION));
    }

    public function editerCommercant(Application $app, $id){
        if($app['session']->get('droit') != 'admin'){
            return $this->manqueDeDroits($app);
        }

        $erreurs = array();
        $donnees['nom'] = $_POST['nom'];
        if((! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom']))){
            $erreurs['nom']="Nom invalide";
        }
        $donnees['id_type_commercant'] = htmlspecialchars($_POST['type']);
        if(!is_numeric($donnees['id_type_commercant'])){
            $erreurs['id_type_commercant'] = "Le type n'est pas valide";
        }
        $donnees['prix_location'] = htmlspecialchars($_POST['tarif']);
        if(!is_numeric($donnees['prix_location'])){
            $erreurs['prix_location'] = "Le prix doit être numérique";
        }
        $donnees['date_installation'] = $_POST['date_installation'];
        if(!DateHelper::verifierDate($donnees['date_installation'])){
            $erreurs['date_installation'] = 'La date n\'a pas un format correct !';
        }else{
            $donnees['date_installation'] = DateHelper::transformerToAmerique($donnees['date_installation']);
        }

        if(!empty($erreurs)) {
            $this->commercantModel = new CommercantModel($app);
            $commercant = $this->commercantModel->getCommercant($id);

            $this->typeCommercantModel = new TypeCommercantModel($app);
            $types = $this->typeCommercantModel->getAllTypeCommercants();

            return $app["twig"]->render("commercant/v_EditerCommercant.twig", array('erreurs' => $erreurs, 'types' => $types, 'commercant'=>$commercant, 'page' => 'edit', 'path' => PATH, 'titre' => 'Edition d\'un commerçant', '_SESSION' => $_SESSION));
        }else{
            $this->commercantModel = new CommercantModel($app);
            $this->commercantModel->editerCommercant($id, $donnees);

            return $app->redirect($app["url_generator"]->generate('commercant.liste'));
        }
    }

    private function manqueDeDroits(Application $app){
        $app['session']->getFlashBag()->add('error', 'Droits insuffisants pour l\'opération demandée');
        return $app->redirect($app["url_generator"]->generate('commercant.liste'));
    }

    private function requeteNonAutorisee(Application $app){
        $app['session']->getFlashBag()->add('error', 'Requête détournée !');
        return $app->redirect($app["url_generator"]->generate('commercant.liste'));
    }

    public function connect(Application $app)
    {
        $index = $app['controllers_factory'];
        $index->match("/", 'App\Controller\CommercantController::index')->bind('index');
        $index->match("/listeCommercant", 'App\Controller\CommercantController::show')->bind('commercant.liste');
        $index->match("/afficherCommercant", 'App\Controller\CommercantController::show');
        $index->get('/supprimerCommercant/{id}', 'App\Controller\CommercantController::formSupprimerCommercant')->assert('id', '\d+')->bind('commercant.supprimer');
        $index->delete("/validSuppressionCommercant/{id}", 'App\Controller\CommercantController::supprimerCommercant')->assert('id', '\d+');
        $index->get("/creerCommercant", 'App\Controller\CommercantController::formCreerCommercant')->bind('commercant.creer');
        $index->post('/validFormCreerCommercant', 'App\Controller\CommercantController::creerCommercant');
        $index->get('/editerCommercant/{id}', 'App\Controller\CommercantController::formEditerCommercant')->assert('id', '\d+')->bind('commercant.editer');
        $index->put('/editerCommercant/{id}', 'App\Controller\CommercantController::editerCommercant')->assert('id', '\d+');
        return $index;
    }
}