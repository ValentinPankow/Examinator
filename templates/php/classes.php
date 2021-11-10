<?php
  //(DH)
  require "sharedVars.php";

  echo $twig->render('classes.twig', array(
      'pageTitle' => 'Examinator - Klassen',
      'userName' => $userName,
      'applicationName' => 'Examinator',
      'pageJs' => 'src/js/classes.js',
      'tpl' => 'classes',
      'darkMode' => $darkMode,
      'classes' => $classes,
      'favoriteClasses' => $favoriteClasses
  ));
