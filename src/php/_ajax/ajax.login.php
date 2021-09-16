<?php

    require_once '../../Core/Container.php';
    require_once '../../Login/LoginRepository.php';
    require_once '../../Login/LoginController.php';
    require_once '../../User/UserRepository.php';
    require_once '../../User/UserModel.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $loginController = $container->make("loginController");

    $user = $loginController->getUserByMail($data->user);

    $obj = new stdClass;
    $obj->user = $user;
    $rtn = json_encode($obj);
    echo $rtn;