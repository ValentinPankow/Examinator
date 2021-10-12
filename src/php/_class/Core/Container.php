<?php

namespace Core;

use PDO;
use Dashboard\DashboardController;
use Dashboard\DashboardRepository;
use Classes\ClassesController;
use Classes\ClassesRepository;
use Exams\ExamsController;
use Exams\ExamsRepository;
use Subjects\SubjectsController;
use Subjects\SubjectsRepository;
use Users\UsersController;
use Users\UsersRepository;
use Favorites\FavoritesController;
use Favorites\FavoritesRepository;

use Login\LoginController;
use Login\LoginRepository;

//Klasse die sich um das erstellen von Objekte kümmert
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
                return new DashboardController($this->make("usersRepository"), $this->make("examsRepository"), $this->make("classesRepository"));
            },
            'dashboardRepository' => function(){
                return new DashboardRepository($this->make("pdo"));
            },
            'loginController' => function(){
                return new LoginController($this->make("loginRepository"), $this->make("usersRepository"));
            },
            'loginRepository' => function(){
                return new LoginRepository($this->make("pdo"));
            },
            'classesController' => function(){
                return new ClassesController($this->make("classesRepository"));
            },
            'classesRepository' => function(){
                return new ClassesRepository($this->make("pdo"));
            },
            'examsController' => function(){
                return new ExamsController($this->make("examsRepository"), $this->make("classesRepository"), $this->make("subjectsRepository"));
            },
            'examsRepository' => function(){
                return new ExamsRepository($this->make("pdo"));
            },
            'subjectsController' => function(){
                return new SubjectsController($this->make("subjectsRepository"));
            },
            'subjectsRepository' => function(){
                return new SubjectsRepository($this->make("pdo"));
            },
            'usersController' => function(){
                return new UsersController($this->make("usersRepository"));
            },
            'usersRepository' => function(){
                return new UsersRepository($this->make("pdo"));
            },
            'favoritesController' => function(){
                return new FavoritesController($this->make("classesRepository"), $this->make("subjectsRepository"));
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
