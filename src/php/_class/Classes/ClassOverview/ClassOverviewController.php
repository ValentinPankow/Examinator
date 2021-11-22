<?php
namespace Classes\ClassOverview;

use Classes\ClassesRepository;
use User\UserRepository;
use Exams\ExamsRepository;

class ClassOverviewController
{
  private $repository;
  private $userRepository;
  private $examsRepository;

  //Übergibt das Repository vom Container
  //(DH)
  public function __construct(ClassesRepository $repository, UserRepository $userRepository, ExamsRepository $examsRepository)
  {
    $this->repository = $repository;
    $this->userRepository = $userRepository;
    $this->examsRepository = $examsRepository;
  }

  //(DH)
  private function render($view, $content)
  {
    $class = $content['class'];
    $class = $content['exams'];
    $twig = $content['twig'];
    $loginState = $content['loginState'];

    include "./templates/php/{$view}.php";
  }

  //Öffnet die Übersicht einer einzelnen Klasse (Für Lehrer/Administratoren)
  //(DH)
  public function index($tpl, $twig, $loginState)
  {
    $userId = $_COOKIE['UserLogin'];
    
    //Falls es ein User ist
    if($userId){
      $user = $this->userRepository->fetchUserById($userId);
      $classId = $_GET['class'];
      $class = $this->repository->fetchClass($classId);
      $exams = $this->examsRepository->fetchClassExams($classId);

      $this->render("{$tpl}", [
          'class' => $class,
          'exams' => $exams,
          'twig' => $twig,
          'loginState' => $loginState
      ]);
    } else {
      header("Refresh:0; url=?page=dashboard");
      exit();
    }
  }

}
