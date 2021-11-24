<?php

    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserController.php';
    require_once '../_class/User/UserModel.php';

    $data = (OBJECT) $_POST['data'];

    $container = new Core\Container();

    $userController = $container->make("userController");

    $user = $userController->getUserDataById($data->id);

    $obj = new stdClass;

    if ($user) {
        $obj->user = $user;
        $obj->success = true;
    } else {
        $obj->success = false;
    }
    
    $rtn = json_encode($obj);
    echo $rtn;