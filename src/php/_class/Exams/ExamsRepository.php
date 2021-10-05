<?php

namespace Exams;
use PDO;
use Exams\ExamsModel;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert
class ExamsRepository
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
    public function fetchExam($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM exams WHERE `id` = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Exams\\ExamsModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    //Fetcht alle Einträge aus der Datenbanktabelle
    //Prepare & Execute werden nicht benötigt, da nach keinen Parametern gefiltert wird
    //Ansonsten siehe fetchKlausuren Kommentare
    public function fetchExams()
    {
        $query = $this->pdo->query("SELECT * FROM exams");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Exams\\ExamsModel");
        
        return $contents;
    }

    public function listExams()
    {
        $query = $this->pdo->prepare("SELECT e.id, s.name AS subject, c.name AS class, e.room, e.lessonFrom, e.lessonTo, e.timeFrom, e.timeTo 
                                    FROM exams AS e 
                                    JOIN classes AS c ON e.class_id = c.id 
                                    JOIN subjects AS s ON e.subject_id = s.id");
        $query->execute();
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Exams\\ExamsModel");
        
        return $contents;
    }

    //Fetcht alle Einträge aus der Datenbanktabelle
    //Prepare & Execute werden nicht benötigt, da nach keinen Parametern gefiltert wird
    //Ansonsten siehe fetchKlausuren Kommentare
    public function fetchUserExams($creatorId)
    {
        
        $query = $this->pdo->prepare("SELECT c.name AS class, s.name AS subject, e.date, e.room, e.topic, e.other 
                                      FROM exams AS e 
                                      JOIN classes AS c ON e.class_id = c.id 
                                      JOIN subjects AS s ON e.subject_id = s.id WHERE `creator_id` = :id");
        $query->execute(['id' => $creatorId]);
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Exams\\ExamsModel");

        return $contents;
    }

    public function insertExam($data) {
        // Change creator ID later
        $query = $this->pdo->prepare("INSERT INTO exams (creator_id, class_id, subject_id, date, room, topic, other, lessonFrom, lessonTo, timeFrom, timeTo)
                                      VALUES (1, :class, :subject, :date, :room, :topic, :other, :lessonFrom, :lessonTo, :timeFrom, :timeTo)");

        $lessonFrom = $data->lessonFrom;
        $lessonTo = $data->lessonTo;
        $timeFrom = $data->timeFrom;
        $timeTo = $data->timeTo;

        if ($lessonFrom == "" || $lessonFrom == 0 || $lessonFrom == "-") {
            $lessonFrom = null;
        }
        if ($lessonTo == "" || $lessonTo == 0 || $lessonTo == "-") {
            $lessonTo = null;
        }
        if ($timeFrom == "") {
            $timeFrom = null;
        }
        if ($timeTo == "") {
            $timeTo = null;
        }

        $result = $query->execute([
            'class' => $data->class,
            'subject' => $data->subject,
            'date' => $data->date,
            'room' => $data->room,
            'topic' => $data->topic,
            'other' => $data->other,
            'lessonFrom' => $lessonFrom,
            'lessonTo' => $lessonTo,
            'timeFrom' => $timeFrom,
            'timeTo' => $timeTo
        ]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteExam($id) {
        $query = $this->pdo->prepare("DELETE FROM exams WHERE id = :id");
        $result = $query->execute(['id' => $id]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}