<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/Login/LoginRepository.php';
    require_once '../_class/Login/LoginController.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserModel.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $loginController = $container->make("loginController");

    $user = $loginController->getUserByMail($data->user);

    $obj = new stdClass;
    $obj->user = $user;
    $rtn = json_encode($obj);
    echo $rtn;