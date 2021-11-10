<?php
    //Ajax um ein Fach zu löschen
    //(DH)


    require_once '../_class/Core/Container.php';
    require_once '../_class/Subjects/SubjectManagement/SubjectManagementController.php';
    require_once '../_class/Subjects/SubjectsModel.php';
    require_once '../_class/Subjects/SubjectsRepository.php';
    require_once '../_class/User/UserRepository.php';

    $data = (object) $_POST['data'];

    $container = new Core\Container();

    $subjectmanagementController = $container->make("subjectmanagementController");

    $ok = $subjectmanagementController->deleteSubject($data->id);

    $obj = new stdClass;

    $obj->success = $ok ? true : false;

    $rtn = json_encode($obj);
    echo $rtn;
