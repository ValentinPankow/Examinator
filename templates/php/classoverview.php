<?php
  //(DH)
  require "sharedVars.php";

  echo $twig->render('classoverview.twig', array(
      'pageTitle' => 'Examinator - Klassen',
      'applicationName' => 'Examinator',
      'pageJs' => 'src/js/classoverview.js',
      'tpl' => 'classoverview',
      'darkMode' => $darkMode,
      'class' => $class,
      'isAdmin' => $isAdmin,
      'isTeacher' => $isTeacher,
      'firstname' => $firstname,
      'lastname' => $lastname,
      'className' => $className
  ));