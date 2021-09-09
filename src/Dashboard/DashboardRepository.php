<?php

namespace Dashboard;
use PDO;

//Klasse die sich um die Datenbankverbindung und dessen Abfragen kümmert
class DashboardRepository
{
    private $pdo;

    //Übergibt die PDO Verbindung vom Container
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

}