<?php

    require "sharedVars.php";

    echo $twig->render('dashboard.twig', array(
        'pageTitle' => 'Examinator - Dashboard',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'user' => $user,
        'exams' => $exams,
        'pageJs' => 'src/js/dashboard.js',
        'tpl' => 'dashboard',
        'darkMode' => $darkMode,
        'isAdmin' => $isAdmin,
        'isTeacher' => $isTeacher,
        'loginState' => $loginState,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'className' => $className
    ));