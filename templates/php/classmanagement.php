<?php

    require "sharedVars.php";

    echo $twig->render('classmanagement.twig', array(
        'pageTitle' => 'Examinator - Klassenverwaltung',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/classmanagement.js',
        'tpl' => 'classmanagement',
        'darkMode' => $darkMode
    ));