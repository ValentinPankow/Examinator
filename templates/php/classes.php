<?php
  //(DH)

  require "sharedVars.php";

  echo $twig->render('classes.twig', array(
      'pageTitle' => 'Examinator - Klassen',
      'applicationName' => 'Examinator',
      'pageJs' => 'src/js/classes.js',
      'tpl' => 'classes',
      'darkMode' => $darkMode,
      'loginState' => $loginState,
      'isAdmin' => $isAdmin,
      'isTeacher' => $isTeacher,
      'firstname' => $firstname,
      'lastname' => $lastname,
      'className' => $className,
      'classes' => $classes,
      'favoriteClasses' => $favoriteClasses
  ));