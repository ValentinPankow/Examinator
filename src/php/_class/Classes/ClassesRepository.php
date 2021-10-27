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
        $query = $this->pdo->query("SELECT * FROM classes");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

        return $contents;
    }

    public function fetchClassData()
    {
        $query = $this->pdo->query("SELECT id, name FROM classes");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

        return $contents;
    }

    public function queryClass($data, $action, &$duplicate = false) {
        if (isset($data->changePassword)) {
            $changePassword = $data->changePassword == "true" ? true : false;
        } else {
            $changePassword = false;
        }

        if ($action == 'insert') {
        $query = $this->pdo->prepare("INSERT INTO classes 
                                    (name, password) 
                                    VALUES 
                                    (:name, :password)");
        } else if ($action == 'update') {
            if ($changePassword) {
                $query = $this->pdo->prepare("UPDATE classes
                                            SET name = :name, password = :password");
            } else {
                $query = $this->pdo->prepare("UPDATE classes 
                                            SET name = :name, password = :password");
            }
        }


        $name = $data->name;
        $password = $data->password;

        $values = array (
            'name' => $name
        );

        if ($action == 'insert' || ($action == 'update' && $changePassword)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $values['password'] = $hashedPassword;
        }

        if ($action == 'update') {
            $values['id'] = $data->id;
            $queryDuplicate = $this->pdo->prepare("SELECT COUNT (id) AS rowsFound FROM classes WHERE name = :name AND id != :id");
            $resultDuplicate = $queryDuplicate->execute(['name' => $name, 'id' => $data->id]);
        } else {
            $queryDuplicate = $this->pdo->prepare("SELECT COUNT(id) AS rowsFound FROM classes WHERE name = :name");
            $resultDuplicate = $queryDuplicate->execute(['name' => $name]);
        }

        $fetchDuplicate = $queryDuplicate->fetchAll(PDO::FETCH_CLASS, "Classes\\ClassesModel");

        if ($fetchDuplicate[0]->rowsFound <= 0) {
            $result = $query->execute($values);
        } else {
            $duplicate = true;
            $result = false;
        }

        if ($result && !$duplicate) {
            return true;
        } else {
            return false;
        }
    }

    public function getClassDataById($id)
    {
        $query = $this->pdo->prepare("SELECT id, name FROM classes WHERE `id` = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Classes\\ClassesModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    public function deleteClassById($id) {
        
        $query = $this->pdo->prepare("DELETE FROM users_classes WHERE user_id = :id");
        $result = $query->execute(['id' => $id]);

        $query = $this->pdo->prepare("DELETE FROM classes WHERE id = :id");
        $result = $query->execute(['id' => $id]);

        $query = $this->pdo->prepare("DELETE FROM exams WHERE creator_id = :id");
        $result = $query->execute(['id' => $id]);

        $query = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $result = $query->execute(['id' => $id]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}