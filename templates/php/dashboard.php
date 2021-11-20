<?php

  //(DH)
  require "sharedVars.php";

  if($login_type == 'teacher'){
    echo $twig->render('dashboard.twig', array(
      'pageTitle' => 'Examinator - Dashboard',
      'applicationName' => 'Examinator',
      'pageJs' => 'src/js/dashboard.js',
      'tpl' => 'dashboard',
      'exams' => $exams,
      'classes' => $classes,
      'login_type' => $login_type,
      'darkMode' => $darkMode,
      'userName' => $user->first_name $user->last_name,
      'isAdmin' => $isAdmin,
      'isTeacher' => $isTeacher,
      'firstname' => $firstname,
      'lastname' => $lastname,
      'loginState' => $loginState,
      'className' => $className
    ));
  }elseif($login_type == 'class'){
    echo $twig->render('dashboard.twig', array(
      'pageTitle' => 'Examinator - Dashboard',
      'applicationName' => 'Examinator',
      'pageJs' => 'src/js/dashboard.js',
      'tpl' => 'dashboard',
      'exams' => $exams,
      'class' => $class,
      'login_type' => $login_type,
      'darkMode' => $darkMode,
      'userName' => $class->name,
      'isAdmin' => $isAdmin,
      'isTeacher' => $isTeacher,
      'firstname' => $firstname,
      'lastname' => $lastname,
      'loginState' => $loginState,
      'className' => $className
    ));
  }