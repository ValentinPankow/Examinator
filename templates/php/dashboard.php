<?php


    require "sharedVars.php";

    echo $twig->render('dashboard.twig', array(
        'pageTitle' => 'Examinator - Dashboard',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'user' => $user,
        'exams' => $exams,
        'classes' => $classes,
        'pageJs' => 'src/js/dashboard.js',
        'tpl' => 'dashboard'
        'darkMode' => $darkMode
    ));