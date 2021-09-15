<?php
namespace Dashboard;

use User\UserRepository;
use Klausuren\KlausurenRepository;
use Dashboard\DashboardRepository;

class DashboardController
{
    private $userRepository;
    private $klausurenRepository;

    //Übergibt das Repository vom Container
    public function __construct(UserRepository $userRepository, KlausurenRepository $klausurenRepository)
    {
        $this->userRepository = $userRepository;
        $this->klausurenRepository = $klausurenRepository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe Dashboard()
    private function render($view, $content)
    {
        $twig = $content['twig'];
        $user = $content['user'];
        $klausuren = $content['klausuren'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Dashboards aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    public function index($tpl, $twig)
    {
        //Example für fetchAll (SELECT * FROM Dashboards)
        // $Dashboards = $this->repository->fetchDashboards();
        $user = $this->userRepository->fetchUser(2);
        $klausuren = $this->klausurenRepository->fetchUserKlausuren($user->id);

        $this->render("{$tpl}", [
            'twig' => $twig,
            'user' => $user,
            'klausuren' => $klausuren
        ]);
    }

}

?>