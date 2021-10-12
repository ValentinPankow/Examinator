<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/Exams/UserRepository.php';
    require_once '../_class/Exams/UserController.php';
    require_once '../_class/Exams/UserModel.php';

    $data = (OBJECT) $_POST['data'];

    $container = new Core\Container();

    $userController = $container->make("userController");

    $user = $userController->fetchUserById($data->id);

    $obj = new stdClass;

    if ($user) {
        $obj->user = $user;
        $obj->success = true;
    } else {
        $obj->success = false;
    }
    
    $rtn = json_encode($obj);
    echo $rtn;