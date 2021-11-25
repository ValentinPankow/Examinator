<?php

namespace UserManagement;
use PDO;
use User\UserModel;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert (GR)
class UserManagementRepository
{
    private $pdo;

    //Übergibt die PDO Verbindung vom Container (GR)
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}