<?php

    require "sharedVars.php";

    echo $twig->render('exams.twig', array(
        'pageTitle' => 'Examinator - Klausuren',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/exams.js',
        'tpl' => 'exams',
        'darkMode' => $darkMode,
        'classes' => $classes,
        'subjects' => $subjects,
        'loginState' => $loginState,
        'isAdmin' => $isAdmin,
        'isTeacher' => $isTeacher,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'className' => $className
    ));
