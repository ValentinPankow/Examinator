<?php

namespace Core;

use PDO;
use Bar\BarController;
use Bar\BarRepository;

use Foo\FooController;
use Foo\FooRepository;

//Klasse die sich um das erstellen anderer Klassen kümmert
class Container
{
    //Enthält alle Objekte die von make() erstellt werden
    private $instances = [];

    //Enthält die Templates zur Erstellung der Objekte
    private $receipts = [];

    //Wird benötigt, da man die Funktionen nicht beim Deklarieren zuweisen kann
    //Hier werden die neuen Controller/Repositorys erstellt
    public function __construct()
    {
        $this->receipts = [
            'barController' => function(){
                return new BarController($this->make("barRepository"));
            },
            'barRepository' => function(){
                return new BarRepository($this->make("pdo"));
            },
            'fooController' => function(){
                return new FooController($this->make("fooRepository"));
            },
            'fooRepository' => function(){
                return new FooRepository($this->make("pdo"));
            },
            //Stellt DB Verbindung her und gibt Sie zurück, falls das Objekt eine braucht
            'pdo' => function(){
                $pdo = new PDO('mysql:host=localhost;dbname=planner;charset=utf8',
                'root',
                '');
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                return $pdo;
            }
        ];
    }

    //Erstellt alle benötigten Objekte
    //z.B. Controller und deren benötigten Repositorys und Models sowie die Datenbankverbindung
    //Überprüft zusätzlich, dass keine Objekte mehrfach erzeugt werden. Falls bereits eines existiert, gibt es dieses zurück
    public function make($name)
    {
        //Überprüft ob es bereits eine aktive Instanz mit dem Objekt gibt, falls ja gibt es das Objekt zurück
        if(!empty($this->instances[$name])){
            return $this->instances[$name];
        }

        //Falls es noch keine aktive Instanz gibt, wird überprüft, ob es ein Template zur Erstellung gibt
        if(isset($this->receipts[$name])){
            $this->instances[$name] = $this->receipts[$name]();
        }

        //Gibt das aktuelle Objekt zurück (Entweder NULL oder wurde grade durch das Template erstellt)
        return $this->instances[$name];
    }
}

?>