<?php
namespace Dashboard;

use Dashboard\DashboardRepository;

class DashboardController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe Dashboard()
    private function render($view, $content)
    {
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Dashboards aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    public function index($tpl, $twig)
    {
        //Example für fetchAll (SELECT * FROM Dashboards)
        // $Dashboards = $this->repository->fetchDashboards();

        $this->render("{$tpl}", [
            // 'Dashboards' => $Dashboards,
            'twig' => $twig
        ]);
    }

}

?>