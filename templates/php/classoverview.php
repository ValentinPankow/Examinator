<?php
  //(DH)
  require "sharedVars.php";

  echo $twig->render('classoverview.twig', array(
      'pageTitle' => 'Examinator - Klassen',
      'userName' => $userName,
      'applicationName' => 'Examinator',
      'pageJs' => 'src/js/classoverview.js',
      'tpl' => 'classoverview',
      'darkMode' => $darkMode,
      'class' => $class,
  ));
