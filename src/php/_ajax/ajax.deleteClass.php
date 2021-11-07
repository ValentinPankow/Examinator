<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/ClassManagement/ClassManagementController.php';
    require_once '../_class/Classes/ClassesModel.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/User/UserRepository.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $classmanagementController = $container->make("classmanagementController");

    $ok = $classmanagementController->deleteExam($data->id);

    $obj = new stdClass;

    if ($ok) {
        $obj->success = true;
    } else {
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;