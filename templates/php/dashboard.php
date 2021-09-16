<?php

    echo $twig->render('dashboard.twig', array(
        'pageTitle' => 'Examinator - Dashboard',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'user' => $user,
        'exams' => $exams,
        'pageJs' => 'src/js/dashboard.js',
        'tpl' => 'dashboard'
    ));