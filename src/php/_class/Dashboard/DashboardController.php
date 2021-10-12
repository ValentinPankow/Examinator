<?php
namespace Dashboard;

use Users\UsersRepository;
use Exams\ExamsRepository;
use Classes\ClassesRepository;
use Dashboard\DashboardRepository;

class DashboardController
{
    private $usersRepository;
    private $examsRepository;
    private $classesRepository;
    private $subjectRepository;

    //Übergibt das Repository vom Container
    public function __construct(UsersRepository $usersRepository, ExamsRepository $examsRepository, ClassesRepository $classesRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->examsRepository = $examsRepository;
        $this->classesRepository = $classesRepository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe Dashboard()
    private function render($view, $content, $login_type)
    {
        $twig = $content['twig'];
        $exams = $content['exams'];

        if($login_type == 'teacher'){
          $classes = $content['classes'];
          $user = $content['user'];
        }elseif($login_type == 'class'){
          $class = $content['class'];
        }


        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Dashboards aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    public function index($tpl, $twig)
    {

    //Testweise als ob eine Klasse oder Lehrer wäre. (!tpl = Klasse; tpl = Lehrer)
      if($tpl){
        $login_type = 'teacher';
        $user = $this->usersRepository->fetchUserById(1);
        $exams = $this->examsRepository->fetchUserExams($user->id);
        $classes = $this->classesRepository->fetchClasses();

        $this->render("{$tpl}", [
          'twig' => $twig,
          'user' => $user,
          'classes' => $classes,
          'exams' => $exams,
          ],
          'teacher'
        );
      }else{
        $login_type = 'class';
        $class = $this->classesRepository->fetchByName('12ITa');
        $exams = $this->examsRepository->fetchClassExams($class->id);

        $this->render("{$tpl}", [
          'twig' => $twig,
          'class' => $class,
          'exams' => $exams
          ],
          $login_type);
        }
    }
}
