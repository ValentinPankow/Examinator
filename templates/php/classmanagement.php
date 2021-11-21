<?php

  //(DH)
  require "sharedVars.php";

  echo $twig->render('classmanagement.twig', array(
    'pageTitle' => 'Examinator - Klassenverwaltung',
    'userName' => $userName,
    'applicationName' => 'Examinator',
    'pageJs' => 'src/js/classmanagement.js',
    'tpl' => 'classmanagement',
    'darkMode' => $darkMode,
    'classes' => $classes,
    'isAdmin' => $isAdmin,
    'isTeacher' => $isTeacher,
    'firstname' => $firstname,
    'lastname' => $lastname,
    'className' => $className
  ));
