<?php

    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/Exams/ExamsRepository.php';
    require_once '../_class/Exams/ExamsController.php';
    require_once '../_class/Exams/ExamsModel.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Subjects/SubjectsRepository.php';

    $container = new Core\Container();

    $examsController = $container->make("examsController");

    $exams = $examsController->listFavoriteExams($_COOKIE['UserLogin']);

    $obj = new stdClass;
    $obj->exams = $exams;
    $rtn = json_encode($obj);
    echo $rtn;