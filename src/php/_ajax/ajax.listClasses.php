<?php

    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Classes/ClassesController.php';
    require_once '../_class/Classes/ClassesModel.php';

    $container = new Core\Container();

    $classController = $container->make("classesController");

    $classes = $classController->listClasses();

    $obj = new stdClass;
    $obj->classes = $classes;
    $rtn = json_encode($obj);
    echo $rtn;