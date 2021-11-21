<?php

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
    //(DH)
    public function fetchFavoriteClasses($userId)
    {
      $query = $this->pdo->prepare("SELECT classes.name, classes.id FROM classes INNER JOIN user_favorites ON user_favorites.class_id = classes.id WHERE user_favorites.user_id = :id ORDER BY classes.name ASC");
      $ok = $query->execute(['id' => $userId]);
      $contents = $ok ? $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel") : false;

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
    //(DH, C&P von VP mit Anpassungen)
    public function queryClass($data, $action, &$duplicate = false)
    {
      //Leerzeichen vor und nach dem Name löschen
      $data->name = trim($data->name);

      if ($action == "insert") {
        $data->password = password_hash($data->password, PASSWORD_DEFAULT);
        $query = $this->pdo->prepare("INSERT INTO classes (name, password) VALUES (:name, :password)");
        $result = $query->execute(['name' => $data->name, 'password' => $data->password]);
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

      return $result;
    }

    //Löscht eine Klasse nach der ID
    //(DH)
    public function deleteClass($id)
    {
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
