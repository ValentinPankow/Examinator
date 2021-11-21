<?php

    //Ajax um eine einzelne Klasse in die Datenbank einzuspeichern oder zu updaten
    //(DH)

    require_once '../_class/Core/Container.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Classes/ClassesModel.php';
    require_once '../_class/Classes/ClassManagement/ClassManagementController.php';
    require_once '../_class/User/UserRepository.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $classManagementController = $container->make("classmanagementController");

    $duplicate = false;
    $ok = $classManagementController->queryClass($data, $data->action, $duplicate);

    $obj = new stdClass;

    if ($ok) {
        $obj->success = true;
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

