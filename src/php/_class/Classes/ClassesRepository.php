<?php

namespace Classes;
use PDO;
use Classes\ClassesModel;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert
class ClassesRepository
{
    private $pdo;

    //Übergibt die PDO Verbindung vom Container
    //(DH)
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    //Holt eine einzelne Klasse nach ID
    //(DH)
    public function fetchClass($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM classes WHERE id = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Classes\\ClassesModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }


    //Holt alle Klassen
    //(DH)
    public function fetchClasses()
    {
        $query = $this->pdo->query("SELECT * FROM classes ORDER BY name ASC");

        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

        return $contents;
    }

    //Sucht eine Klasse nach dem Namen heraus
    //(DH)
    public function fetchByName($name)
    {
        $query = $this->pdo->prepare("SELECT * FROM classes WHERE `name` = :name LIMIT 1");
        $query->execute(['name' => $name]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Classes\\ClassesModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }


    //Holt alle favorisierten Fächer von einem User
    //(DH)
    public function fetchFavoriteClasses($userId)
    {
      $query = $this->pdo->prepare("SELECT `classes`.`name`, `classes`.`id` FROM `classes` INNER JOIN `user_favorites` ON `user_favorites`.`class_id` = `classes`.`id` WHERE `user_favorites`.`user_id` = :id ORDER BY `classes`.`name` ASC");
      $query->execute(['id' => $userId]);
      $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

      return $contents;
    }

    //(DH)
    public function fetchClassesWithoutFavorites($classIds)
    {
      $query = $this->pdo->query("SELECT id, name FROM classes WHERE id NOT IN ($classIds) ORDER BY name ASC");
      // $query->execute(['classIds' => $classIds]);
      $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

      return $contents;
    }

    public function queryClass($data, $action)
    {
        if ($action == "insert") {
            $query = $this->pdo->prepare("INSERT INTO classes (name, password) VALUES (:name, :password)");
            $result = $query->execute(['name' => $data->name, 'password' => $data->password]);
        } else if ($action == "update") {
            if($data->password != ""){
                $query = $this->pdo->prepare("UPDATE classes SET name = :name, password = :password WHERE id = :id");
                $result = $query->execute(['name' => $data->name, 'password' => $data->password, 'id' => $data->id]);
            } else {
                $query = $this->pdo->prepare("UPDATE classes SET name = :name WHERE id = :id");
                $result = $query->execute(['name' => $data->name, 'id' => $data->id]);
            }
        } else {
            return false;
        }

        if ($result) {
            return true;
        } else {
            return false;
        }
    }


    public function deleteExam($id)
    {
        $query = $this->pdo->prepare("DELETE FROM classes WHERE id = :id");
        $result = $query->execute(['id' => $id]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
