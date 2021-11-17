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
        'userName' => "$user->first_name $user->last_name",));
    }elseif($login_type == 'class'){
      echo $twig->render('dashboard.twig', array(
        'pageTitle' => 'Examinator - Dashboard',
        'applicationName' => 'Examinator',
        'pageJs' => 'src/js/dashboard.js',
        'tpl' => 'dashboard',
<<<<<<< HEAD
        'exams' => $exams,
        'class' => $class,
        'login_type' => $login_type,
        'darkMode' => $darkMode,
        'userName' => $class->name,));
    }


=======
        'darkMode' => $darkMode,
        'isAdmin' => $isAdmin,
        'isTeacher' => $isTeacher,
        'loginState' => $loginState,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'className' => $className
    ));
>>>>>>> dev
