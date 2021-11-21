<?php
namespace ClassManagement;
use PDO;
use Class\ClassesModel;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert
class ClassManagementRepository{
    private $pdo;

    //Übergibt die PDO Verbindung vom Container
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}