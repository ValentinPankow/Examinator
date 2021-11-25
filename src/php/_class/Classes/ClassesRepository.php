<?php

// DH

namespace Classes;
use PDO;
use Classes\ClassesModel;

//Klasse die sich um die Datenbankabfragen der Klassen kümmert
//(DH)
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

    //Holt alle favorisierten Fächer eines Users
    //(DH & VP)
    public function fetchFavoriteClasses($userId, $setupPage = true)
    {
      $query = $this->pdo->prepare("SELECT classes.name, classes.id FROM classes INNER JOIN user_favorites ON user_favorites.class_id = classes.id WHERE user_favorites.user_id = :id ORDER BY classes.name ASC");
      $ok = $query->execute(['id' => $userId]);
      $contents = $ok ? $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel") : false;
      // (VP) Return all Classes if User has no Favorites
      if (count($contents) == 0 && !$setupPage) {
        $contents = $this->fetchClasses();
      }

      return $contents;
    }

    //Holt alle nicht favorisierten Fächer eines Users
    //(DH)
    public function fetchClassesWithoutFavorites($classIds)
    {
      $query = $this->pdo->query("SELECT id, name FROM classes WHERE id NOT IN ($classIds) ORDER BY name ASC");
      $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

      return $contents;
    }

    //Erstellt bzw. updated eine Klasse und gibt eine Erfolgs/Fehlermeldung zurück
    //(DH)
    public function queryClass($data, $action, &$duplicate, &$data_id = -1)
    {
      //Leerzeichen vor und nach dem Name löschen
      $data->name = trim($data->name);

      //Überprüft auf ein Duplikat (Name)
      $classes = $this->fetchClasses();

      foreach($classes AS $class){
        if ($action == "import") {
          if(strtolower($class->name) == strtolower($data->name)){
            $duplicate = true;
            break;
          }
        } else {
          if(strtolower($class->name) == strtolower($data->name) && $class->id != $data->id){
            $duplicate = true;
            break;
          }
        }
      }

      //Falls kein Duplikat vorhanden ist die Query durchführen, überprüft auf Insert oder Update
      if($duplicate == false){
        if ($action == "insert" || $action == "import") {
          $data->password = password_hash($data->password, PASSWORD_DEFAULT);
          $query = $this->pdo->prepare("INSERT INTO classes (name, password) VALUES (:name, :password)");
          $result = $query->execute(['name' => $data->name, 'password' => $data->password]);

          $query = $this->pdo->prepare("SELECT id FROM classes WHERE name = :name");
          $query->execute(['name' => $data->name]);
          $query->setFetchMode(PDO::FETCH_CLASS, "Classes\\ClassesModel");
          $content = $query->fetch(PDO::FETCH_CLASS);

          $data_id = $content->id;
        } else if ($action == "update") {
          if($data->password != ""){
            $data->password = password_hash($data->password, PASSWORD_DEFAULT);
            $query = $this->pdo->prepare("UPDATE classes SET name = :name, password = :password WHERE id = :id");
            $result = $query->execute(['name' => $data->name, 'password' => $data->password, 'id' => $data->id]);
          } else {
            $query = $this->pdo->prepare("UPDATE classes SET name = :name WHERE id = :id");
            $result = $query->execute(['name' => $data->name, 'id' => $data->id]);
          }
        } else {
          $result = false;
        }
      }else{
        $result = false;
      }

      return $result;
    }

    //Löscht eine Klasse, sowie die dazugehörigen Klausuren und Favoriten
    //(DH)
    public function deleteClass($id)
    {
      //Löscht vorhandene Klausuren 
      $query = $this->pdo->prepare("DELETE FROM exams WHERE subject_id = :id");
      $result = $query->execute(['id' => $id]);

      //Löscht vorhandene eingespeicherte Favoriten 
      $query = $this->pdo->prepare("DELETE FROM user_favorites WHERE subject_id = :id");
      $result = $query->execute(['id' => $id]);

      //Löscht die Klasse
      $query = $this->pdo->prepare("DELETE FROM classes WHERE id = :id");
      $result = $query->execute(['id' => $id]);

      return $result;
    }

    //Löscht alle vorherigen Einträge und fügt dann die neuen Favoriten hinzu
    //(DH)
    public function desert($classIds, $userId)
    {
      //Lösche alle vorherigen Einträge vom User
      $query = $this->pdo->prepare("DELETE FROM user_favorites WHERE user_id = :userId AND class_id != 'NULL'");
      $query->execute(['userId' => $userId]);

      //Fügt die ausgewählten Favoriten hinzu
      foreach($classIds AS $key => $value){
        $query = $this->pdo->prepare("INSERT INTO user_favorites (user_id, class_id, subject_id) VALUES (:userId, :classId, NULL)");
        $query->execute(['userId' => $userId, 'classId' => $value]);
      }
    }

}
