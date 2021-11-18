<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserController.php';
    require_once '../_class/User/UserModel.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $userController = $container->make("userController");

    $ok = $userController->deleteUserById($data->id);

    $obj = new stdClass;

    if ($ok) {
        $obj->success = true;
    } else {
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;