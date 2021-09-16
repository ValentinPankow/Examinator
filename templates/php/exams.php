<?php

    echo $twig->render('dashboard.twig', array(
        'pageTitle' => 'Examinator - Klausuren',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'exams' => $exams,
        'pageJs' => 'src/js/exams.js',
        'tpl' => 'exams'
    ));