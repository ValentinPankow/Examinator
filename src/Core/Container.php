<?php

namespace Core;

use PDO;
use Dashboard\DashboardController;
use Dashboard\DashboardRepository;
use Klassen\KlassenController;
use Klassen\KlassenRepository;
use Klausuren\KlausurenController;
use Klausuren\KlausurenRepository;
use User\UserController;
use User\UserRepository;

use Login\LoginController;
use Login\LoginRepository;

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
            'dashboardController' => function(){
                return new DashboardController($this->make("userRepository"), $this->make("klausurenRepository"));
            },
            'dashboardRepository' => function(){
                return new DashboardRepository($this->make("pdo"));
            },
            'loginController' => function(){
                return new LoginController($this->make("loginRepository"));
            },
            'loginRepository' => function(){
                return new LoginRepository($this->make("pdo"));
            },
            'klassenController' => function(){
                return new KlassenController($this->make("klassenRepository"));
            },
            'klassenRepository' => function(){
                return new KlassenRepository($this->make("pdo"));
            },
            'klausurenController' => function(){
                return new KlausurenController($this->make("klausurenRepository"));
            },
            'klausurenRepository' => function(){
                return new KlausurenRepository($this->make("pdo"));
            },
            'userController' => function(){
                return new UserController($this->make("userRepository"));
            },
            'userRepository' => function(){
                return new UserRepository($this->make("pdo"));
            },
            //Stellt DB Verbindung her und gibt Sie zurück, falls das Objekt eine braucht
            'pdo' => function(){
                $pdo = new PDO('mysql:host=localhost;dbname=examinator;charset=utf8',
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