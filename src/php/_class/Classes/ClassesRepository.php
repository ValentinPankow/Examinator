<?php

namespace Classes;
use PDO;
use Classes\ClassesModel;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert
class ClassesRepository
{
    private $pdo;

    //Übergibt die PDO Verbindung vom Container
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    //Fetcht einen Eintrag aus der Datenbanktabelle
    //Prepare & Execute werden benötigt wegen SQL Injektions :param und execute :param => $param ist hier Standard
    //FetchMode wird nur bei einem einzelnen Fetch benötigt
    //PDO::FETCH_CLASS wandelt das Array in die Attribute der Klasse um
    //Achtung! Die Namenskonvention des Models muss gleich der Datenbank sein (ansonsten AS benutzen)
    public function fetchClass($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM classes WHERE `id` = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Classes\\ClassesModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    //Fetcht alle Einträge aus der Datenbanktabelle
    //Prepare & Execute werden nicht benötigt, da nach keinen Parametern gefiltert wird
    //Ansonsten siehe fetchClasses Kommentare
    public function fetchClasses()
    {
        $query = $this->pdo->query("SELECT * FROM classes ORDER BY name ASC");

        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

        return $contents;
    }

    //Sucht eine Klasse nach dem Namen heraus
    public function fetchByName($name)
    {
        $query = $this->pdo->prepare("SELECT * FROM classes WHERE `name` = :name LIMIT 1");
        $query->execute(['name' => $name]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Classes\\ClassesModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    //Sucht eine Klasse nach dem Namen heraus
    public function fetchTeacherClasses($name)
    {

    }

    //Holt alle favorisierten Fächer von einem User
    public function fetchFavoriteClasses($userId)
    {
      $query = $this->pdo->prepare("SELECT `classes`.`name`, `classes`.`id` FROM `classes` INNER JOIN `user_favorites` ON `user_favorites`.`class_id` = `classes`.`id` WHERE `user_favorites`.`user_id` = :id ORDER BY `classes`.`name` ASC");
      $query->execute(['id' => $userId]);
      $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

      return $contents;
    }

    public function fetchClassesWithoutFavorites($classIds)
    {
      $query = $this->pdo->prepare("SELECT id, name FROM classes WHERE id NOT IN (:classIds) ORDER BY name ASC");
      $query->execute(['classIds' => $classIds]);
      $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

      return $contents;
    }

}
