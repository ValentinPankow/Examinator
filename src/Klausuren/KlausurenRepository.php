<?php

namespace Klausuren;
use PDO;
use Klausuren\KlausurenModel;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert
class KlausurenRepository
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
    public function fetchKlausur($id)
    {
        $query = $this->pdo->prepare("SELECT * from exams WHERE `id` = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Klausuren\\KlausurenModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    //Fetcht alle Einträge aus der Datenbanktabelle
    //Prepare & Execute werden nicht benötigt, da nach keinen Parametern gefiltert wird
    //Ansonsten siehe fetchKlausuren Kommentare
    public function fetchKlausuren()
    {
        $query = $this->pdo->query("SELECT * from exams");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Klausuren\\KlausurenModel");

        return $contents;
    }

    //Fetcht alle Einträge aus der Datenbanktabelle
    //Prepare & Execute werden nicht benötigt, da nach keinen Parametern gefiltert wird
    //Ansonsten siehe fetchKlausuren Kommentare
    public function fetchUserKlausuren($creatorId)
    {
        
        $query = $this->pdo->prepare("SELECT * from exams WHERE `creator_id` = :id");
        $query->execute(['id' => $creatorId]);
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Klausuren\\KlausurenModel");

        return $contents;
    }
}