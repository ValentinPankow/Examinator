<?php

    require "sharedVars.php";

    echo $twig->render('users.twig', array(
        'pageTitle' => 'Examinator - Benutzerverwaltung',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/users.js',
        'tpl' => 'users',
        'darkMode' => $darkMode,
        'userName' => 'User',
        'users' => $users,

    ));
