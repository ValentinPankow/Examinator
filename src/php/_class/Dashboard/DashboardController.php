<?php
namespace Dashboard;

use User\UserRepository;
use Exams\ExamsRepository;
use Classes\ClassesRepository;
use Dashboard\DashboardRepository;

class DashboardController
{
    private $userRepository;
    private $examsRepository;
    private $classesRepository;
    private $subjectRepository;

    //Übergibt das Repository vom Container
    public function __construct(UserRepository $userRepository, ExamsRepository $examsRepository, ClassesRepository $classesRepository)
    {
        $this->userRepository = $userRepository;
        $this->examsRepository = $examsRepository;
        $this->classesRepository = $classesRepository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe Dashboard()
    private function render($view, $content)
    {
        $twig = $content['twig'];
        $user = $content['user'];
        $exams = $content['exams'];
        $classes = $content['classes'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Dashboards aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    public function index($tpl, $twig)
    {
        //Example für fetchAll (SELECT * FROM Dashboards)
        // $Dashboards = $this->repository->fetchDashboards();
      
        $exams = null;
        $user = $this->userRepository->fetchUserById(1);
        if ($user) {
            $exams = $this->examsRepository->fetchUserExams($user->id);
        }

        $classes = $this->classesRepository->fetchClasses();

        $this->render("{$tpl}", [
            'twig' => $twig,
            'user' => $user,
            'classes' => $classes,
            'exams' => $exams,
        ]);
    }

}

?>