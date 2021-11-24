<?php

    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/Exams/ExamsRepository.php';
    require_once '../_class/Exams/ExamsController.php';
    require_once '../_class/Exams/ExamsModel.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Subjects/SubjectsRepository.php';

    $data = (OBJECT) $_POST['data'];

    $container = new Core\Container();

    $examsController = $container->make("examsController");

    $exam = $examsController->fetchExam($data->id);

    $obj = new stdClass;

    if ($exam) {
        $obj->exam = $exam;
        $obj->success = true;
    } else {
        $obj->success = false;
    }
    
    $rtn = json_encode($obj);
    echo $rtn;