<?php

    echo $twig->render('exams.twig', array(
        'pageTitle' => 'Examinator - Klausuren',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/exams.js',
        'tpl' => 'exams'
    ));