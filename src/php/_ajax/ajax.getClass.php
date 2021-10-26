<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Classes/ClassesController.php';
    require_once '../_class/Classes/ClassesModel.php';

    $data = (OBJECT) $_POST['data'];

    $container = new Core\Container();

    $classController = $container->make("classesController");

    $class = $classController->getClassDataById($data->id);

    $obj = new stdClass;

    if ($user) {
        $obj->class = $class;
        $obj->success = true;
    } else {
        $obj->success = false;
    }
    
    $rtn = json_encode($obj);
    echo $rtn;