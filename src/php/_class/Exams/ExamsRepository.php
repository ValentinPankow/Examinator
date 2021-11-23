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


    //Holt sich ein einzelnes Exam nach ID (DH)
    public function fetchExam($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM exams WHERE id = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Exams\\ExamsModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }


    //Holt sich alle Examen(DH)
    public function fetchExams()
    {
        $query = $this->pdo->query("SELECT * FROM exams");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Exams\\ExamsModel");

        return $contents;
    }

    //(VP)
    public function listExams()
    {
        $query = $this->pdo->prepare("SELECT e.id, s.name AS subject, c.name AS class, e.date, e.room, e.lessonFrom, e.lessonTo, e.timeFrom, e.timeTo
                                    FROM exams AS e
                                    JOIN classes AS c ON e.class_id = c.id
                                    JOIN subjects AS s ON e.subject_id = s.id
                                    ORDER BY created_at DESC");
        $query->execute();
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Exams\\ExamsModel");

        return $contents;
    }

    // (VP)
    public function listFavoriteExams($userId) 
    {
        $query = $this->pdo->prepare("SELECT e.id, s.name AS subject, c.name AS class, e.date, e.room, e.lessonFrom, e.lessonTo, e.timeFrom, e.timeTo
                                    FROM exams AS e
                                    JOIN classes AS c ON e.class_id = c.id
                                    JOIN subjects AS s ON e.subject_id = s.id
                                    JOIN user_favorites AS uf ON uf.class_id = e.class_id
                                    WHERE uf.user_id = :userId 
                                    ORDER BY created_at DESC");
        $query->execute(['userId'=>$userId]);
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Exams\\ExamsModel");
        // List all exams if there are no Favorites
        if (count($contents) == 0) {
            $contents = $this->listExams();
        }

        return $contents;
    }

    //(DH)
    public function fetchUserExams($creatorId, $limit = NULL)
    {
        if($limit){
          $query = $this->pdo->prepare("SELECT c.name AS class, s.name AS subject, e.date, e.room, e.topic, e.other, e.lessonFrom, e.lessonTo, e.timeFrom, e.timeTo
                                        FROM exams AS e
                                        JOIN classes AS c ON e.class_id = c.id
                                        JOIN subjects AS s ON e.subject_id = s.id WHERE `creator_id` = :id
                                        ORDER BY e.date, e.lessonFrom, e.timeFrom ASC
                                        LIMIT :limit");
          $query->execute(['id' => $creatorId, 'limit' => $limit]);
        }else{
          $query = $this->pdo->prepare("SELECT c.name AS class, s.name AS subject, e.date, e.room, e.topic, e.other, e.lessonFrom, e.lessonTo, e.timeFrom, e.timeTo
                                        FROM exams AS e
                                        JOIN classes AS c ON e.class_id = c.id
                                        JOIN subjects AS s ON e.subject_id = s.id WHERE `creator_id` = :id
                                        ORDER BY e.date, e.timeFrom ASC");
          $query->execute(['id' => $creatorId]);
        }

        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Exams\\ExamsModel");

        return $contents;
    }

    //(DH)
    public function fetchClassExams($classId, $limit = NULL)
    {
        if($limit){
        $query = $this->pdo->prepare("SELECT c.name AS class, s.name AS subject, e.date, e.room, e.topic, e.other, e.lessonFrom, e.lessonTo, e.timeFrom, e.timeTo
                                      FROM exams AS e
                                      JOIN classes AS c ON e.class_id = c.id
                                      JOIN subjects AS s ON e.subject_id = s.id WHERE `class_id` = :id
                                      ORDER BY e.date, e.lessonFrom, e.timeFrom ASC
                                      LIMIT :limit");
        $query->execute(['id' => $classId, 'limit' => $limit]);
        }else{
          $query = $this->pdo->prepare("SELECT c.name AS class, s.name AS subject, e.date, e.room, e.topic, e.other, e.lessonFrom, e.lessonTo, e.timeFrom, e.timeTo
                                        FROM exams AS e
                                        JOIN classes AS c ON e.class_id = c.id
                                        JOIN subjects AS s ON e.subject_id = s.id WHERE `class_id` = :id
                                        ORDER BY e.date, e.timeFrom ASC");
          $query->execute(['id' => $classId]);
        }
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

        return $contents;
    }

    //(VP)
    public function queryExam($data, $action, $userId) {
        // Change creator ID later
        if ($action == "insert") {
            $query = $this->pdo->prepare("INSERT INTO exams (creator_id, class_id, subject_id, date, room, topic, other, lessonFrom, lessonTo, timeFrom, timeTo)
                                        VALUES (:userId, :class, :subject, :date, :room, :topic, :other, :lessonFrom, :lessonTo, :timeFrom, :timeTo)");
        } else if ($action == "update") {
            $query = $this->pdo->prepare(
                "UPDATE exams
                 SET class_id = :class, subject_id = :subject, date = :date, room = :room, topic = :topic, other = :other, lessonFrom = :lessonFrom, lessonTo = :lessonTo,
                 timeFrom = :timeFrom, timeTo = :timeTo
                 WHERE id = :id");
        } else {
            return false;
        }

        $lessonFrom = $data->lessonFrom;
        $lessonTo = $data->lessonTo;
        $timeFrom = $data->timeFrom;
        $timeTo = $data->timeTo;
        $topic = $data->topic;
        $other = $data->other;

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
        if ($topic == "") {
            $topic = null;
        }
        if ($other == "") {
            $other = null;
        }

        $values = array(
            'class' => $data->class,
            'subject' => $data->subject,
            'date' => $data->date,
            'room' => $data->room,
            'topic' => $topic,
            'other' => $other,
            'lessonFrom' => $lessonFrom,
            'lessonTo' => $lessonTo,
            'timeFrom' => $timeFrom,
            'timeTo' => $timeTo
        );

        if ($action == "update") {
            $values['id'] = $data->id;
        }

        if ($action == "insert") {
            $values['userId'] = $userId;
        }

        $result = $query->execute($values);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    //(VP)
    public function deleteExam($id) {
        $query = $this->pdo->prepare("DELETE FROM exams WHERE id = :id");
        $result = $query->execute(['id' => $id]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteClassExamsByIdAndDateRange($classId, $dateFrom, $dateTo, $deleteAll, &$examsExist) {
        $result = false;
        $deleteAll = $deleteAll == "true" ? true : false; 

        if (!$deleteAll) {
            $query = $this->pdo->prepare("SELECT COUNT(id) as rowCount FROM exams WHERE class_id = :id AND date >= :dateFrom AND date <= :dateTo");
            $query->execute(['id' => $classId, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo]);
        } else {
            $query = $this->pdo->prepare("SELECT COUNT(id) as rowCount FROM exams WHERE date >= :dateFrom AND date <= :dateTo");
            $query->execute(['dateFrom' => $dateFrom, 'dateTo' => $dateTo]);
        }
        $content = $query->fetchAll(PDO::FETCH_DEFAULT);

        if ($content[0]['rowCount'] > 0) {
            if (!$deleteAll) {
                $query = $this->pdo->prepare("DELETE FROM exams WHERE class_id = :id AND date >= :dateFrom AND date <= :dateTo");
                $result = $query->execute(['id' => $classId, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo]);
            } else {
                $query = $this->pdo->prepare("DELETE FROM exams WHERE date >= :dateFrom AND date <= :dateTo");
                $result = $query->execute(['dateFrom' => $dateFrom, 'dateTo' => $dateTo]);
            }
        } else {
            $examsExist = false;
        }

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
