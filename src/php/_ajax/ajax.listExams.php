<?php

    require_once '../_class/Core/Container.php';
    require_once '../_class/Exams/ExamsRepository.php';
    require_once '../_class/Exams/ExamsController.php';

    $container = new Core\Container();

    $examsController = $container->make("examsController");

    $exams = $examsController->listExams();

    $obj = new stdClass;
    $obj->exams = $exams;
    $rtn = json_encode($obj);
    echo $rtn;