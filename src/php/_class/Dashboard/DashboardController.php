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
  //(DH)
  public function __construct(UserRepository $userRepository, ExamsRepository $examsRepository, ClassesRepository $classesRepository)
  {
    $this->userRepository = $userRepository;
    $this->examsRepository = $examsRepository;
    $this->classesRepository = $classesRepository;
  }

<<<<<<< HEAD
  //(DH)
  private function render($view, $content, $login_type)
  {
    $twig = $content['twig'];
    $exams = $content['exams'];
=======
    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe Dashboard()
    private function render($view, $content)
    {
        $twig = $content['twig'];
        $user = $content['user'];
        $exams = $content['exams'];
        $loginState = $content['loginState'];
>>>>>>> dev

    if($login_type == 'teacher'){
      $classes = $content['classes'];
      $user = $content['user'];
    }elseif($login_type == 'class'){
      $class = $content['class'];
    }

    include "./templates/php/{$view}.php";
  }

<<<<<<< HEAD
  //Öffnet das Dashboard (Klasse, Lehrer oder Administrator)
  //(DH)
  public function index($tpl, $twig)
  {
  //Testweise als ob eine Klasse oder Lehrer wäre. (!tpl = Klasse; tpl = Lehrer)
    if($tpl){
      $login_type = 'teacher';
      $user = $this->userRepository->fetchUserById(1);
      $exams = $this->examsRepository->fetchUserExams($user->id, 6);
      $classes = $this->classesRepository->fetchClasses();
=======
    //Sucht sich alle Dashboards aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    public function index($tpl, $twig, $loginState)
    {
        //Example für fetchAll (SELECT * FROM Dashboards)
        // $Dashboards = $this->repository->fetchDashboards();
        $exams = null;
        $user = $this->userRepository->fetchUserById(1);
        if ($user) {
            $exams = $this->examsRepository->fetchUserExams($user->id);
        }

        $this->render("{$tpl}", [
            'twig' => $twig,
            'user' => $user,
            'exams' => $exams,
            'loginState' => $loginState
        ]);
    }
>>>>>>> dev

      $this->render("{$tpl}", [
        'twig' => $twig,
        'user' => $user,
        'classes' => $classes,
        'exams' => $exams,
        ],
        'teacher'
      );
    } else {
      $login_type = 'class';
      $class = $this->classesRepository->fetchByName('12ITa');
      $exams = $this->examsRepository->fetchClassExams($class->id, 6);

      $this->render("{$tpl}", [
        'twig' => $twig,
        'class' => $class,
        'exams' => $exams
        ],
        $login_type);
    }
  }
}
