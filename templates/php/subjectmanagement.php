<?php
  //(DH)
  require "sharedVars.php";

  echo $twig->render('subjectmanagement.twig', array(
      'pageTitle' => 'Examinator - Klassenverwaltung',
      'userName' => $userName,
      'applicationName' => 'Examinator',
      'pageJs' => 'src/js/subjectmanagement.js',
      'tpl' => 'subjectmanagement',
      'darkMode' => $darkMode,
      'subjects' => $subjects,
  ));
