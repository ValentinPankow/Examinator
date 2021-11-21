<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Classes/ClassesController.php';
    require_once '../_class/Classes/ClassesModel.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $classController = $container->make("classesController");

    $duplicate = false;
    $ok = $classController->queryClass($data, $data->action, $duplicate);

    $obj = new stdClass;

    if ($ok) {
        $obj->success = true;
    } else {
        if ($duplicate) {
            $obj->error = "duplicate";
        } else {
            $obj->error = "insert";
        }
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;