<?php 

    require './config.php';

    if (str_contains($_SERVER['REQUEST_URI'], 'index.php')) {
        header("HTTP/1.1 301 Moved Permanently"); 
        header('Location: /examinator/?page=login');
    } else {
        $pageController->display();
    }