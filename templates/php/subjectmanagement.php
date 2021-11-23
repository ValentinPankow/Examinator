<?php
  //(DH)
  require "sharedVars.php";

  echo $twig->render('subjectmanagement.twig', array(
    'pageTitle' => 'Examinator - Klassenverwaltung',
    'applicationName' => 'Examinator',
    'pageJs' => 'src/js/subjectmanagement.js',
    'tpl' => 'subjectmanagement',
    'darkMode' => $darkMode,
    'subjects' => $subjects,
    'loginState' => $loginState,
    'isAdmin' => $isAdmin,
    'isTeacher' => $isTeacher,
    'firstname' => $firstname,
    'lastname' => $lastname,
    'className' => $className
  ));
