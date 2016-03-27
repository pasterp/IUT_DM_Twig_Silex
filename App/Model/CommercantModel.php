<?php

/**
 * Created by PhpStorm.
 * User: pphelipo
 * Date: 11/02/16
 * Time: 08:56
 */

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class CommercantModel
{
    private $connexionSQL;

    public function __construct(Application $app){
        $this->connexionSQL = $app['db'];
    }

    public function getAllCommercants(){
        $queryBuilder = new QueryBuilder($this->connexionSQL);
        $queryBuilder
            ->select('c.id_commercant', 'c.nom', 'c.date_installation', 'c.id_type_commercant', 'c.prix_location', 't.noms')
            ->from('commercant', 'c')->leftJoin('c', 'type_commercant', 't', 'c.id_type_commercant=t.id_type')->addOrderBy('c.id_commercant', 'ASC');

        return $queryBuilder->execute()->fetchAll();
    }

    public function insertCommercant($donnees){
        $queryBuilder = new QueryBuilder($this->connexionSQL);
        $queryBuilder
            ->insert('commercant')
            ->values([
                'nom' => ':nom', 'id_type_commercant' => ':id_type_commercant', 'prix_location' => ':prix_location', 'date_installation' => ':date_installation'
            ])->setParameters($donnees);
        return $queryBuilder->execute();
    }

    public function removeCommercant($id){
        $queryBuilder = new QueryBuilder($this->connexionSQL);
        $queryBuilder->delete('commercant')->where('id_commercant = :id')->setParameter('id', (int)$id);

        $queryBuilder->execute();
    }

    public function getCommercant($id){
        $queryBuilder = new QueryBuilder($this->connexionSQL);
        $queryBuilder->select('c.id_commercant', 'c.nom', 'c.date_installation', 'c.id_type_commercant', 'c.prix_location')
        ->from('commercant', 'c')->where('c.id_commercant = :id')->setParameter('id', (int)$id);
        return $queryBuilder->execute()->fetch();
    }

    public function editerCommercant($id, $donnees){
        $donnees['id_commercant'] = $id;
        $queryBuilder = new QueryBuilder($this->connexionSQL);

        $queryBuilder->update('commercant')
            ->set('nom', ':nom')
            ->set('id_type_commercant', ':id_type_commercant')
            ->set('date_installation', ':date_installation')
            ->set('prix_location', ':prix_location')
            ->where('id_commercant = :id_commercant')
            ->setParameters($donnees);

        $queryBuilder->execute();
    }

/* TODO: EDITER COMMERCANT
    public function editerCommercant($id, $donnees)
    {
        $req = "UPDATE commercant SET nom=:nom, id_type_commercant=:id_type_commercant, date_installation=:date_installation, prix_location=:prix_location WHERE id_commercant='".$id."'";
        $req = $this->connexionSQL->prepare($req);
        return $req->execute($donnees);
    }*/
}