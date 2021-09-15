<?php

    echo $twig->render('login.twig', array(
        'pageTitle' => 'Examinator - Login',
        'applicationName' => 'Examinator',
        'subPage' => true
    ));