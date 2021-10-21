<?php

namespace Subjects;
use PDO;
use Subjects\SubjectsModel;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert
class SubjectsRepository
{
    private $pdo;

    //Übergibt die PDO Verbindung vom Container
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchSubjects()
    {
        $query = $this->pdo->query("SELECT * FROM subjects");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Subjects\\SubjectsModel");

        return $contents;
    }

}