<?php
  //(DH)
  require "sharedVars.php";

  echo $twig->render('favorites.twig', array(
    'pageTitle' => 'Examinator - Favoriten',
    'userName' => 'User',
    'applicationName' => 'Examinator',
    'pageJs' => 'src/js/favorites.js',
    'tpl' => 'favorites',
    'darkMode' => $darkMode,
    'userId' => $userId,
    'favoriteClasses' => $favoriteClasses,
    'favoriteSubjects' => $favoriteSubjects,
    'classes' => $classes,
    'subjects' => $subjects,
    'isAdmin' => $isAdmin,
    'isTeacher' => $isTeacher,
    'firstname' => $firstname,
    'lastname' => $lastname,
    'className' => $className
  ));
