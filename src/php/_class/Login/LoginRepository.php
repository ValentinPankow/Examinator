<?php

// EE & VP

namespace Login;
use PDO;
use Login\LoginModel;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert
class LoginRepository
{
    private $pdo;

    //Übergibt die PDO Verbindung vom Container
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
}
