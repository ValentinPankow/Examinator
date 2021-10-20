<?php

namespace User;
use PDO;
use User\UserModel;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert
class UserRepository
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
    public function fetchUserById($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE `id` = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "User\\UserModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    public function getUserDataById($id)
    {
        $query = $this->pdo->prepare("SELECT id, first_name, last_name, email, is_admin, is_teacher FROM users WHERE `id` = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "User\\UserModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    public function fetchUserByMail($mail)
    {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE `email` = :mail");
        $query->execute(['mail' => $mail]);
        $query->setFetchMode(PDO::FETCH_CLASS, "User\\UserModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    //Fetcht alle Einträge aus der Datenbanktabelle
    //Prepare & Execute werden nicht benötigt, da nach keinen Parametern gefiltert wird
    //Ansonsten siehe fetchUser Kommentare
    public function fetchUsers()
    {
        $query = $this->pdo->query("SELECT * FROM users");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "User\\UserModel");

        return $contents;
    }

    public function fetchUserData()
    {
        $query = $this->pdo->query("SELECT id, first_name, last_name, email, is_admin, is_teacher FROM users");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "User\\UserModel");

        return $contents;
    }

    public function queryUser($data, $action, &$duplicate = false) {
        if ($action == 'insert') {
            $query = $this->pdo->prepare("INSERT INTO users 
                                          (first_name, last_name, email, password, is_admin, is_teacher) 
                                          VALUES 
                                          (:firstname, :lastname, :email, :password, :isAdmin, :isTeacher)");
        } else if ($action == 'update') {
            if ($data->changePassword) {
                $query = $this->pdo->prepare("UPDATE users 
                                            SET first_name = :firstname, last_name = :lastname, email = :email, password = :password, is_admin = :isAdmin, is_teacher = :isTeacher 
                                            WHERE id = :id");
            } else {
                $query = $this->pdo->prepare("UPDATE users 
                                            SET first_name = :firstname, last_name = :lastname, email = :email, is_admin = :isAdmin, is_teacher = :isTeacher 
                                            WHERE id = :id");
            }

        }
        
        $firstname = $data->firstname;
        $lastname = $data->lastname;
        $email = $data->email;
        $password = $data->password;
        $isAdmin = $data->isAdmin == "true" ? 1 : 0;
        $isTeacher = $data->isTeacher == "true" ? 1 : 0;

        $values = array (
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'isAdmin' => $isAdmin,
            'isTeacher' => $isTeacher
        );

        if ($action == 'insert' || ($action == 'update' && $data->changePassword)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        } 
        

        if ($data->changePassword && $action == 'update') {

            $values['password'] = $hashedPassword;
            $queryDuplicate = $this->pdo->prepare("SELECT COUNT(id) AS rowsFound FROM users WHERE email = :email AND id != :id");
            $resultDuplicate = $queryDuplicate->execute(['email' => $email, 'id' => $data->id]);
        } else {
            $queryDuplicate = $this->pdo->prepare("SELECT COUNT(id) AS rowsFound FROM users WHERE email = :email");
            $resultDuplicate = $queryDuplicate->execute(['email' => $email]); 
        }

        $fetchDuplicate = $queryDuplicate->fetchAll(PDO::FETCH_CLASS, "User\\UserModel");
        
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
}