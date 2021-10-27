<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Classes/ClassesController.php';
    require_once '../_class/Classes/ClassesModel.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $classController = $container->make("classesController");

    $ok = $classController->deleteClassById($data->id);

    $obj = new stdClass;

    if ($ok) {
        $obj->success = true;
    } else {
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;