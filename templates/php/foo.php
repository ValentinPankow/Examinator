<?php

    /*foreach($bars as $bar){
        echo $bar->name . "<hr>";
    }*/

    echo $twig->render('foo.twig', array(
        'foos' => $foos,
        'pageTitle' => 'Foo'
    ));