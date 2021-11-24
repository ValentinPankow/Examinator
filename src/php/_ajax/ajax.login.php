<?php

    // VP & EE

    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/Login/LoginRepository.php';
    require_once '../_class/Login/LoginController.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserModel.php';
    require_once '../_class/Classes/ClassesModel.php';


    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $loginController = $container->make("loginController");

    $login = $loginController->login($data->user, $data->password);

    $obj = new stdClass;
    $obj->success = $login;
    $rtn = json_encode($obj);
    echo $rtn;