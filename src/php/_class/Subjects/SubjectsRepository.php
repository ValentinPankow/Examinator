<?php

namespace Subjects;
use PDO;
use Subjects\SubjectsModel;


class SubjectsRepository
{
    private $pdo;

    //Übergibt die PDO Verbindung vom Container
    //(DH)
    public function __construct(PDO $pdo)
    {
      $this->pdo = $pdo;
    }

    //Holt ein einzelnes Fach nach ID
    //(DH)
    public function fetchSubject($id)
    {
      $query = $this->pdo->prepare("SELECT * FROM subjects WHERE `id` = :id");
      $query->execute(['id' => $id]);
      $query->setFetchMode(PDO::FETCH_CLASS, "Subjects\\SubjectsModel");
      $content = $query->fetch(PDO::FETCH_CLASS);

      return $content;
    }

    //Holt alle Fächer
    //(DH)
    public function fetchSubjects()
    {
      $query = $this->pdo->query("SELECT * FROM subjects ORDER BY name ASC");
      $contents = $query->fetchAll(PDO::FETCH_CLASS, "Subjects\\SubjectsModel");

      return $contents;
    }

    //Holt alle favorisierten Fächer von einem User
    //(DH & VP)
    public function fetchFavoriteSubjects($userId, $setupPage = true)
    {
      $query = $this->pdo->prepare("SELECT subjects.name, subjects.id FROM subjects INNER JOIN user_favorites ON user_favorites.subject_id = subjects.id WHERE user_favorites.user_id = :id ORDER BY name ASC");
      $ok = $query->execute(['id' => $userId]);
      $contents = $ok ? $query->fetchAll(PDO::FETCH_CLASS, "Subjects\\SubjectsModel") : false;
      // (VP) Return all Classes if User has no Favorites
      if (count($contents) == 0 && !$setupPage) {
        $contents = $this->fetchSubjects();
      }

      return $contents;
    }

    //Holt alle favorisierten Fächer von einem User
    //(DH)
    public function fetchSubjectsWithoutFavorites($subjectIds)
    {
      $query = $this->pdo->query("SELECT id, name FROM subjects WHERE id NOT IN ($subjectIds) ORDER BY name ASC");
      $contents = $query->fetchAll(PDO::FETCH_CLASS, "Subjects\\SubjectsModel");

      return $contents;
    }

    //Erstellt bzw. updated ein Fach und gibt eine Erfolgs/Fehlermeldung zurück
    //(DH, C&P von VP mit Anpassungen)
    public function querySubject($data, $action, &$duplicate)
    {
      //Leerzeichen vor und nach dem Name löschen
      $data->name = trim($data->name);

      //Überprüft auf ein Duplikat (Name)
      $subjects = $this->fetchSubjects();

      foreach($subjects AS $subject){
        if($subject->name == $data->name){
          $duplicate = true;
          break;
        }
      }

      //Falls kein Duplikat vorhanden ist die Query durchführen, überprüft auf Insert oder Update
      if($duplicate == false){
        if ($action == "insert") {
          $query = $this->pdo->prepare("INSERT INTO subjects (name) VALUES (:name)");
          $result = $query->execute(['name' => $data->name]);
        } else if ($action == "update") {
          $query = $this->pdo->prepare("UPDATE subjects SET name = :name WHERE id = :id");
          $result = $query->execute(['name' => $data->name, 'id' => $data->id]);
        } else {
          $result = false;
        }
      } else{
        $result = false;
      }

      return $result;
    }

    //Löscht ein Fach, sowie die dazugehörigen Klausuren und Favoriten
    //(DH)
    public function deleteSubject($id)
    {
      //Löscht vorhandene Klausuren 
      $query = $this->pdo->prepare("DELETE FROM exams WHERE subject_id = :id");
      $result = $query->execute(['id' => $id]);

      //Löscht vorhandene eingespeicherte Favoriten 
      $query = $this->pdo->prepare("DELETE FROM user_favorites WHERE subject_id = :id");
      $result = $query->execute(['id' => $id]);

      //Löscht das Fach
      $query = $this->pdo->prepare("DELETE FROM subjects WHERE id = :id");
      $result = $query->execute(['id' => $id]);

      return $result;
    }

    //Löscht alle vorherigen Einträge und fügt dann die neuen Favoriten hinzu
    //(DH)
    public function desert($subjectIds, $userId)
    {
      //Lösche alle vorherigen Einträge vom User
      $query = $this->pdo->prepare("DELETE FROM user_favorites WHERE user_id = :userId AND subject_id != 'NULL'");
      $query->execute(['userId' => $userId]);

      //Fügt die ausgewählten Favoriten hinzu
      foreach($subjectIds AS $subjectId){
        $query = $this->pdo->prepare("INSERT INTO user_favorites (user_id, class_id, subject_id) VALUES (:userId, NULL, :subjectId)");
        $query->execute(['userId' => $userId, 'subjectId' => $subjectId]);
      }
    }

}
