<?php
    //Ajax um ein einzelnes Fach in die Datenbank einzuspeichern oder zu updaten
    //(DH)

    require_once '../_class/Core/Container.php';
    require_once '../_class/Subjects/SubjectManagement/SubjectManagementController.php';
    require_once '../_class/Subjects/SubjectsModel.php';
    require_once '../_class/Subjects/SubjectsRepository.php';
    require_once '../_class/User/UserRepository.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $subjectManagementController = $container->make("subjectmanagementController");

    $duplicate = false;
    $ok = $subjectManagementController->querySubject($data, $data->action, $duplicate);

    $obj = new stdClass;

    if ($ok) {
        $obj->success = true;
    } else {
        if ($duplicate) {
            $obj->error = "duplicate";
        } else {
            $obj->error = "failed";
        }
        $obj->success = false;
    }

    $rtn = json_encode($obj);
    echo $rtn;
