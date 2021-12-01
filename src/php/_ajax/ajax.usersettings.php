<?php

    // VP & GR

    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserController.php';
    require_once '../_class/User/UserModel.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $userController = $container->make("userController");

    $duplicate = false;
    $passwordOk = false;

    // Aufruf der Funktion, zum Ausführen eines SQL-Befehl, aus dem Controller (GR)
    $ok = $userController->saveUsersettings($data, $duplicate, $passwordOk);

    $obj = new stdClass;

    if ($ok) {
        $obj->success = true;
    } else {
        if ($duplicate) {
            $obj->error = "duplicate";
        } else if (!$passwordOk) {
            $obj->error = "wrong_pwd";
        } else {
            $obj->error = "update";
        }
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;