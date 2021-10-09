<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserController.php';
    require_once '../_class/User/UserModel.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $userController = $container->make("userController");

    $ok = $userController->queryUser($data, $data->action);

    $obj = new stdClass;

    if ($ok == "success") {
        $obj->success = true;
    } else {
        if ($ok == "insertError") {
            $obj->error = "insert";
        } else {
            $obj->error = "duplicate";
        }
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;