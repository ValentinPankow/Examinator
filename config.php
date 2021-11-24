<?php

    require './src/php/_class/PageController.php';

    $pageController = new PageController();

    // (VP) Redirect URL to avoid Break Page layout
    define("REDIRECT_URL", "https://localhost/examinator/?page=login");