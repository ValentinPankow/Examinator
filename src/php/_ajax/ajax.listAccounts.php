<?php

    // GR & VP

    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserController.php';
    require_once '../_class/User/UserModel.php';

    $container = new Core\Container();

    $userController = $container->make("userController");

    $users = $userController->listAccounts();

    $obj = new stdClass;
    $obj->accounts = $users;
    $rtn = json_encode($obj);
    echo $rtn;