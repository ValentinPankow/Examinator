<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/ClassManagement/ClassManagementController.php';
    require_once '../_class/User/UserRepository.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $classManagementController = $container->make("classmanagementController");

    $ok = $classManagementController->queryClass($data, $data->action);

    $obj = new stdClass;

    if ($ok) {
        $obj->success = true;
    } else {
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;