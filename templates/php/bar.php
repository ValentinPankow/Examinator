<?php

    /*foreach($bars as $bar){
        echo $bar->name . "<hr>";
    }*/

    echo $twig->render('bar.twig', array(
        'bars' => $bars,
        'pageTitle' => 'Bars'
    ));