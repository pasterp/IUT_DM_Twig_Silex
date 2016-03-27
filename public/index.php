<?php


    if(strpos($_SERVER['REQUEST_URI'], 'index.php')){
        require_once '../App/bootstrap.php';
    }else{
        header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'index.php');
    }

