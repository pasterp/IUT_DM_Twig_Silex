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

    public function getType($id){
        $queryBuilder = new QueryBuilder($this->connexionSQL);
        $queryBuilder
            ->select('id_type', 'noms')
            ->from('type_commercant')
            ->where('id_type = :id')
            ->setParameter('id', $id);

        return $queryBuilder->execute()->fetch();
    }

    public function removeType($id){
        $queryBuilder = new QueryBuilder($this->connexionSQL);
        $queryBuilder
            ->delete('type_commercant')
            ->where('id_type = :id')
            ->setParameter('id', $id);

        $queryBuilder->execute();
    }

    public function editType($id, $donnees){
        $queryBuilder = new QueryBuilder($this->connexionSQL);
        $queryBuilder
            ->update('type_commercant')
            ->set('noms', ':noms')
            ->where('id_type = :id')
            ->setParameters($donnees)
            ->setParameter(':id', $id);

        $queryBuilder->execute();
    }

    public function addType($donnees){
        $queryBuilder = new QueryBuilder($this->connexionSQL);
        $queryBuilder->insert('type_commercant')
            ->values(['noms' => ':noms'])->setParameters($donnees);

        $queryBuilder->execute();
    }
}