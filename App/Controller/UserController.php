<?php

/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 26/03/16
 * Time: 19:26
 */

namespace App\Controller;

use App\Model\UserModel;
use Silex\Application;
use Silex\ControllerProviderInterface;


class UserController implements ControllerProviderInterface
{
    private $userModel;

    public function logout(Application $app){
        $app['session']->clear();
        $app['session']->getFlashBag()->add('msg', 'Vous êtes déconnecté');
        return $app->redirect($app["url_generator"]->generate('commercant.liste'));
    }

    public function login(Application $app){
        $username = $_POST['username'];
        $motdepasse = $_POST['password'];

        $this->userModel = new UserModel($app);
        $donnees = $this->userModel->verifierLoginMdp($username, $motdepasse);

        if($donnees == false){
            $app['session']->getFlashBag()->add('error', 'Identifiants invalides !');
            return $app->redirect($app["url_generator"]->generate('commercant.liste'));
        }else{
            $app['session']->clear();
            $app['session']->getFlashBag()->add('success', "Connexion réussie !");

            $app['session']->set('logged', 1);
            $app['session']->set('droit', $donnees['droits_user']);
            $app['session']->set('user', $donnees['nom_user']);


            return $app->redirect($app["url_generator"]->generate('commercant.liste'));
        }
    }

    public function connect(Application $app)
    {
        $index = $app['controllers_factory'];
        $index->get('/logout', 'App\Controller\UserController::logout')->bind('user.logout');
        $index->post('/login', 'App\Controller\UserController::login')->bind('user.login');
        return $index;
    }
}