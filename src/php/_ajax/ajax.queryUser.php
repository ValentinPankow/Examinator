<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserController.php';
    require_once '../_class/User/UserModel.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $userController = $container->make("userController");

    $duplicate = false;
    $ok = $userController->queryUser($data, $data->action, $duplicate);

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