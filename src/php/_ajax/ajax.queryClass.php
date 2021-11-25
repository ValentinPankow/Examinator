<?php

    //Ajax um eine einzelne Klasse in die Datenbank einzuspeichern oder zu updaten
    //(DH)
    
    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Classes/ClassesModel.php';
    require_once '../_class/Classes/ClassManagement/ClassManagementController.php';
    require_once '../_class/User/UserRepository.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $classManagementController = $container->make("classmanagementController");

    $duplicate = false;
    $data_id = -1;
    $ok = $classManagementController->queryClass($data, $data->action, $duplicate, $data_id);

    $obj = new stdClass;

    if ($ok) {
        $obj->success = true;
        $obj->data_id = $data_id;
    } else {
        if ($duplicate) {
            $obj->error = "duplicate";
        } else {
            $obj->error = "failed";
        }
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;

