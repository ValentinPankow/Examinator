<?php

    require "sharedVars.php";

    echo $twig->render('classes.twig', array(
        'pageTitle' => 'Examinator - Klassen',
        'userName' => 'User',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/classes.js',
        'tpl' => 'classes',
        'darkMode' => $darkMode
    ));