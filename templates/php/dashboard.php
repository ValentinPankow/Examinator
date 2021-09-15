<?php

    echo $twig->render('dashboard.twig', array(
        'pageTitle' => 'Examinator - Dashboard',
        'userName' => 'User',
        'applicationName' => 'Examinator'
    ));