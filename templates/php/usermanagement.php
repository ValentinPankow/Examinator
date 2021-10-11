<?php

    require "sharedVars.php";

    echo $twig->render('usermanagement.twig', array(
        'pageTitle' => 'Examinator - Benutzerverwaltung',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/usermanagement.js',
        'tpl' => 'usermanagement',
        'darkMode' => $darkMode
    ));