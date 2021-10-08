<?php

namespace Subjects;
use PDO;
use Subjects\SubjectsModel;


class SubjectsRepository
{
    private $pdo;

    //Übergibt die PDO Verbindung vom Container
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    //Holt ein einzelnes Fach nach ID
    public function fetchSubject($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM subjects WHERE `id` = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Subjects\\SubjectsModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    //Holt alle Fächer
    public function fetchSubjects()
    {
        $query = $this->pdo->query("SELECT * FROM subjects ORDER BY name ASC");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Subjects\\SubjectsModel");

        return $contents;
    }

    //Holt alle favorisierten Fächer von einem User
    public function fetchFavoriteSubjects($userId)
    {
      $query = $this->pdo->prepare("SELECT subjects.name, subjects.id FROM subjects INNER JOIN user_favorites ON user_favorites.subject_id = subjects.id WHERE user_favorites.user_id = :id ORDER BY name ASC");
      $query->execute(['id' => $userId]);
      $contents = $query->fetchAll(PDO::FETCH_CLASS, "Subjects\\SubjectsModel");

      return $contents;
    }

    //Holt alle favorisierten Fächer von einem User
    public function fetchSubjectsWithoutFavorites($subjectIds)
    {
      $query = $this->pdo->prepare("SELECT id, name FROM subjects WHERE id NOT IN (:subjectIds) ORDER BY name ASC");
      $query->execute(['subjectIds' => $subjectIds]);
      $contents = $query->fetchAll(PDO::FETCH_CLASS, "Subjects\\SubjectsModel");

      return $contents;
    }


}
