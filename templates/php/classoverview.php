<?php
  //(DH)
  require "sharedVars.php";

  echo $twig->render('classoverview.twig', array(
      'pageTitle' => 'Examinator - Klasse ' . $class->name,
      'applicationName' => 'Examinator',
      'pageJs' => 'src/js/classoverview.js',
      'tpl' => 'classoverview',
      'favoriteClasses' => $favoriteClasses,
      'darkMode' => $darkMode,
      'class' => $class,
      'exams' => $exams,
      'isAdmin' => $isAdmin,
      'isTeacher' => $isTeacher,
      'firstname' => $firstname,
      'lastname' => $lastname,
      'className' => $className,
      'loginState' => $loginState,
  ));
