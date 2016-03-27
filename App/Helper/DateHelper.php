<?php

/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 27/03/16
 * Time: 12:44
 */

namespace App\Helper;

class DateHelper
{
    public static function verifierDate($date){
        if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)){
            $dateParts = explode('-', $date);
            $jour = $dateParts[2];
            $mois = $dateParts[1];
            $annee = $dateParts[0];
        }elseif(preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)){
            $dateParts = explode('/', $date);
            $jour = $dateParts[0];
            $mois = $dateParts[1];
            $annee = $dateParts[2];
        }else{
            return false;
        }
        if(!checkdate($mois, $jour, $annee)){
            return false;
        }else{
            return true;
        }

    }

    public static function transformerToFrance($dateAmerique){
        if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateAmerique)){
            $dateParts = explode('-', $dateAmerique);
            $jour = $dateParts[2];
            $mois = $dateParts[1];
            $annee = $dateParts[0];
            return $jour.'/'.$mois.'/'.$annee;
        }elseif(preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateAmerique)){
            return $dateAmerique;
        }
    }

    public static function transformerToAmerique($dateFrance){
        if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrance)){
            return $dateFrance;
        }elseif(preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateFrance)){
            $dateParts = explode('/', $dateFrance);
            $jour = $dateParts[0];
            $mois = $dateParts[1];
            $annee = $dateParts[2];
            return $annee.'-'.$mois.'-'.$jour;
        }
    }
}