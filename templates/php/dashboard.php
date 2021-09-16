<?php

    echo $twig->render('dashboard.twig', array(
        'pageTitle' => 'Examinator - Dashboard',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'user' => $user,
        'klausuren' => $klausuren,
        'pageJs' => 'src/js/dashboard.js',
        'tpl' => 'dashboard'
    ));