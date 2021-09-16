<?php

    echo $twig->render('classes.twig', array(
        'pageTitle' => 'Examinator - Klassen',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/exams.js',
        'tpl' => 'exams'
    ));