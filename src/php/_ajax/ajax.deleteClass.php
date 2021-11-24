<?php

    //Ajax um eine Klasse zu löschen
    //(DH)

    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/Classes/ClassManagement/ClassManagementController.php';
    require_once '../_class/Classes/ClassesModel.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/User/UserRepository.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $classmanagementController = $container->make("classmanagementController");

    $ok = $classmanagementController->deleteClass($data->id);

    $obj = new stdClass;

    $obj->success = $ok ? true : false;

    $rtn = json_encode($obj);
    echo $rtn;
