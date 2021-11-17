<?php

    require "sharedVars.php";

    echo $twig->render('classmanagement.twig', array(
        'pageTitle' => 'Examinator - Klassenverwaltung',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/classmanagement.js',
        'tpl' => 'classmanagement',
        'darkMode' => $darkMode,
        'loginState' => $loginState,
        'isAdmin' => $isAdmin,
        'isTeacher' => $isTeacher,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'className' => $className
    ));