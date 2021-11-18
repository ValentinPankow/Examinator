<?php

    require "sharedVars.php";

    echo $twig->render('login.twig', array(
        'pageTitle' => 'Examinator - Login',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/login.js',
        'tpl' => 'login',
        'darkMode' => $darkMode,
        'loginState' => $loginState,
        'isAdmin' => $isAdmin,
        'isTeacher' => $isTeacher,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'className' => $className
    ));
