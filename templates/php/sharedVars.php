<?php

    $darkMode = isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] == "true" ? true : false;
    $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1 ? true : false;
    $isTeacher = isset($_SESSION['isTeacher']) && $_SESSION['isTeacher'] == 1 ? true : false; 
    $firstname = isset($_SESSION['firstname']) && strlen($_SESSION['firstname']) > 0 ? $_SESSION['firstname'] : null;
    $lastname = isset($_SESSION['lastname']) && strlen($_SESSION['lastname']) > 0 ? $_SESSION['lastname'] : null;
    $className = isset($_SESSION['class_name']) && strlen($_SESSION['class_name']) > 0 ? $_SESSION['class_name'] : null;
