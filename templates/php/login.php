<?php

    echo $twig->render('login.twig', array(
        'pageTitle' => 'Examinator - Login',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/login.js',
        'tpl' => 'login'
    ));