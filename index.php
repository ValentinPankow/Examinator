<?php 

    // (VP) Ruft im PageController die display Methode auf um die Seite zu laden

    require './config.php';

    if (str_contains($_SERVER['REQUEST_URI'], 'index.php')) {
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: ' . REDIRECT_URL . '?page=login');
    } else {
        $pageController->display();
    }
    