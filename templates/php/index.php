<?php

    echo $twig->render('index.twig', array(
        'pageTitle' => 'Examinator - Dashboard',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'user' => $user,
        'klausuren' => $klausuren,
    ));