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
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

use App\Model\CommercantModel;

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

        if(!empty($erreurs)){
            $this->typeCommercantModel = new TypeCommercantModel($app);
            $typeProduits = $this->typeCommercantModel->getAllTypeCommercants();
            return $app["twig"]->render('commercant/v_CreerCommercant.twig', array('types' => $typeProduits, 'donnees' => $donnees, 'titre'=>"Commercants - Insertion d'un commerçant", 'page' => 'Ajout', 'path' => PATH, 'erreurs' => $erreurs));
        }else{
            $this->commercantModel = new CommercantModel($app);
            $this->commercantModel->insertCommercant($donnees);
            //header('Location: '.BASE_URL.'index.php/Commercant/afficherCommercants/');

            return $this->show($app);
        }

    }

    public function formSupprimerCommercant(Application $app, $id){
        $this->commercantModel = new CommercantModel($app);
        $commercant = $this->commercantModel->getCommercant($id);

        return $app["twig"]->render("commercant/v_SupprimerCommercant.twig", array('commercant' => $commercant, 'page' => 'other', 'path' => PATH, 'titre' => 'Suppresion d\'un commerçant', '_SESSION' => $_SESSION));
    }

    public function supprimerCommercant(Application $app, $id){
        $this->commercantModel = new CommercantModel($app);

        $this->commercantModel->removeCommercant($id);
        return $this->show($app);
    }


    public function connect(Application $app)
    {
        $index = $app['controllers_factory'];
        $index->match("/", 'App\Controller\CommercantController::index');
        $index->get("/listeCommercant", 'App\Controller\CommercantController::show');
        $index->match("/afficherCommercant", 'App\Controller\CommercantController::show');
        $index->get("/creerCommercant", 'App\Controller\CommercantController::formCreerCommercant');
        $index->get('/supprimerCommercant/{id}', 'App\Controller\CommercantController::formSupprimerCommercant')->assert('id', '\d+');
        $index->delete("/validSuppressionCommercant/{id}", 'App\Controller\CommercantController::supprimerCommercant')->assert('id', '\d+');
        $index->post('/validFormCreerCommercant', 'App\Controller\CommercantController::creerCommercant');
        return $index;
    }
}