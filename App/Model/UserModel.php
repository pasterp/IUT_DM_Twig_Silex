<?php

/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 26/03/16
 * Time: 19:17
 */

namespace App\Model;

use Silex\Application;

use Doctrine\DBAL\Query\QueryBuilder;

class UserModel
{
    private $connexionSql;

    public function __construct(Application $app)
    {
        $this->connexionSql = $app['db'];
    }

    public function verifierLoginMdp($username, $motdepasse){
        $queryBuilder = new QueryBuilder($this->connexionSql);
        $queryBuilder->select('nom_user', 'mdp_user', 'droits_user')
            ->from('user_commercant')
            ->where('nom_user = :username and mdp_user = :mdp')
            ->setParameter('username', $username)
            ->setParameter('mdp', md5($motdepasse));
        if($queryBuilder->execute()->rowCount() == 1){
            return $queryBuilder->execute()->fetch();
        }else{
            return false;
        }
    }
}