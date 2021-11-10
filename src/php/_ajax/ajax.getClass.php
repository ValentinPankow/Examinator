<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Classes/ClassesModel.php';
    require_once '../_class/Classes/ClassManagement/ClassManagementController.php';
    require_once '../_class/User/UserRepository.php';

    $data = (OBJECT) $_POST['data'];

    $container = new Core\Container();

    $classManagementController = $container->make("classmanagementController");

    $class = $classManagementController->fetchClass($data->id);

    $obj = new stdClass;

    if ($class) {
        $obj->class = $class;
        $obj->success = true;
    } else {
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;
