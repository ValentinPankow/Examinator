<?php

// (VP & GR)

namespace User;
use PDO;
use User\UserModel;
use Classes\ClassesModel;

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

    // VP Gets Userdata by user ID
    public function getUserDataById($id)
    {
        $query = $this->pdo->prepare("SELECT id, first_name, last_name, email, is_admin, is_teacher FROM users WHERE `id` = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "User\\UserModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

    // VP & EE cheks the given username / mail adress and passwort and if ok logs the user in by setting cookies and session
    public function login($user, $password)
    {
        $query = $this->pdo->prepare("SELECT password, id FROM users WHERE `email` = :user");
        $query->execute(['user' => $user]);
        $query->setFetchMode(PDO::FETCH_CLASS, "User\\UserModel");
        $resultPwd = $query->fetch(PDO::FETCH_CLASS);

        #$hashedPassword = password_hash($resultPwd->password, PASSWORD_DEFAULT);
        $passwordOk = false;
        if ($resultPwd){
            $passwordOk = password_verify($password, $resultPwd->password);
        }
        
        $query = $this->pdo->prepare("SELECT password, id, name FROM classes WHERE `name` = :user");
        $query->execute(['user' => $user]);
        $query->setFetchMode(PDO::FETCH_CLASS, "Classes\\ClassesModel");
        $resultPwdClasses = $query->fetch(PDO::FETCH_CLASS);
        #$hashedPasswordClasses = password_hash($resultPwdClasses->password, PASSWORD_DEFAULT);
        $passwordClassesOk = false;
        if ($resultPwdClasses){
            $passwordClassesOk = password_verify($password, $resultPwdClasses->password);
        }
        
        $login = false;
        if ($passwordOk && $resultPwd){ 
            session_start();
            session_regenerate_id(true);
            $sesionID = session_id();
            $query = $this->pdo->prepare("UPDATE users SET session_id = :session_id WHERE email = :user");
            $query->execute(['session_id' => $sesionID, "user" => $user]);
            setcookie("UserLogin","", time()-3600, "/" );
            setcookie("ClassesLogin","", time()-3600, "/" );
            // Nach einer stunde läuft der Login ab
            setcookie("UserLogin", $resultPwd->id, time()+(3600*1), "/");

            $userData = $this->getUserDataById($resultPwd->id);
            $_SESSION['isAdmin'] = $userData->is_admin;
            $_SESSION['isTeacher'] = $userData->is_teacher;
            $_SESSION['firstname'] = $userData->first_name;
            $_SESSION['lastname'] = $userData->last_name;

            unset($_SESSION['class_name']);
 
            $login = true;
        } else if ($passwordClassesOk && $resultPwdClasses ){ 
            session_start();
            setcookie("ClassesLogin","", time()-3600, "/" );
            setcookie("UserLogin","", time()-3600, "/" );
            // Nach einer stunde läuft der Login ab
            setcookie("ClassesLogin", $resultPwdClasses->id, time()+(3600*1), "/");
            $_SESSION['class_name'] = $resultPwdClasses->name;
            unset($_SESSION['isAdmin']);
            unset($_SESSION['isTeacher']);
            unset($_SESSION['firstname']);
            unset($_SESSION['lastname']);
            $login = true;
        }
        return $login;
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

    // Fetch users main data
    public function fetchUserData()
    {
        $query = $this->pdo->query("SELECT id, first_name, last_name, email, is_admin, is_teacher FROM users");
        $contents = $query->fetchAll(PDO::FETCH_CLASS, "User\\UserModel");

        return $contents;
    }

    // VP & GR Function does insert or update a user
    public function queryUser($data, $action, &$duplicate = false) {
        if (isset($data->changePassword)) {
            $changePassword = $data->changePassword == "true" ? true : false;
        } else {
            $changePassword = false;
        }

        if ($action == 'insert') {
            $query = $this->pdo->prepare("INSERT INTO users 
                                          (first_name, last_name, email, password, is_admin, is_teacher) 
                                          VALUES 
                                          (:firstname, :lastname, :email, :password, :isAdmin, :isTeacher)");
        } else if ($action == 'update') {
            if ($changePassword) {
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
        $isAdmin = $data->isAdmin == "true" || $data->isAdmin == "1" ? 1 : 0;
        $isTeacher = $data->isTeacher == "true" || $data->isTeacher == "1" ? 1 : 0;

        $values = array (
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'isAdmin' => $isAdmin,
            'isTeacher' => $isTeacher
        );

        if ($action == 'insert' || ($action == 'update' && $changePassword)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $values['password'] = $hashedPassword;
        }

        if ($action == 'update') {
            $values['id'] = $data->id;
            $queryDuplicate = $this->pdo->prepare("SELECT COUNT(id) AS rowsFound FROM users WHERE LOWER(email) = LOWER(:email) AND id != :id");
            $resultDuplicate = $queryDuplicate->execute(['email' => $email, 'id' => $data->id]);
        } else {
            $queryDuplicate = $this->pdo->prepare("SELECT COUNT(id) AS rowsFound FROM users WHERE LOWER(email) = LOWER(:email)");
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

    // VP & GR deletes user by user id
    public function deleteUserById($id) {
        $query = $this->pdo->prepare("DELETE FROM user_favorites WHERE user_id = :id");
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

    // VP & EE returns the session_id from the database
    public function getSessionID($userID){
        $query = $this->pdo->prepare("SELECT session_id FROM users WHERE `id` = :id");
        $query->execute(['id' => $userID]);
        $content = $query->fetch(PDO::FETCH_DEFAULT);

        return $content["session_id"];
    }
}
