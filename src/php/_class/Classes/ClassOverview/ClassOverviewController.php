<?php
namespace Classes\ClassOverview;

use Classes\ClassesRepository;
use Exams\ExamsRepository;

class ClassOverviewController
{
  private $repository;
  private $examsRepository;

  //Übergibt das Repository vom Container
  //(DH)
  public function __construct(ClassesRepository $repository, ExamsRepository $examsRepository)
  {
    $this->repository = $repository;
    $this->examsRepository = $examsRepository;
  }

  //(DH)
  private function render($view, $content)
  {
    $class = $content['class'];
    $exams = $content['exams'];
    $twig = $content['twig'];
    $loginState = $content['loginState'];

    include "./templates/php/{$view}.php";
  }

  //Öffnet die Übersicht einer einzelnen Klasse (Für Lehrer/Administratoren)
  //(DH)
  public function index($tpl, $twig, $loginState)
  {
    $userId = isset($_COOKIE['UserLogin']) ? $_COOKIE['UserLogin'] : false;

    //Falls es ein User ist
    if($userId){
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
