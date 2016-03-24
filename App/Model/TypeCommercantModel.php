<?php

/**
 * Created by PhpStorm.
 * User: pphelipo
 * Date: 11/02/16
 * Time: 08:57
 */

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;


class TypeCommercantModel
{
    private $connexionSQL;

    public function __construct(Application $app){
        $this->connexionSQL = $app['db'];
    }

    public function getAllTypeCommercants(){
        $queryBuilder = new QueryBuilder($this->connexionSQL);
        $queryBuilder
            ->select('id_type', 'noms')->from('type_commercant')->addOrderBy('id_type', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }

    /*
    public function getAllTypeCommercants()
    {
        $requete="SELECT id_type, noms FROM BDD_pphelipo.type_commercant;";
        try {
            $select = $this->connexionSQL->query($requete);
            $results = $select->fetchAll();
            return $results;
        }
        catch ( Exception $e ) {
            echo "Erreur de récupération de tous les commerçants";
        }
    }

    public function getType($id){
        $req = "SELECT id_type, noms FROM type_commercant WHERE id_type='".$id."';";
        try {
            $select = $this->connexionSQL->query($req);
            $results = $select->fetch();
            return $results;
        }
        catch ( Exception $e ) {
            echo "Erreur de récupération de tous les types";
        }
    }

    public function removeType($id){
        $req = "DELETE FROM type_commercant WHERE id_type='".$id."';";
        $this->connexionSQL->exec($req);
    }

    public function editType($id, $donnees){
        $req = "UPDATE type_commercant SET noms=:noms WHERE id_type='".$id."';";
        $req = $this->connexionSQL->prepare($req);
        $req->execute($donnees);
    }
    */
}