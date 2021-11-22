<?php
namespace Classes\ClassOverview;

use Classes\ClassesRepository;
use User\UserRepository;

class ClassOverviewController
{
  private $repository;
  private $userRepository;

  //Übergibt das Repository vom Container
  //(DH)
  public function __construct(ClassesRepository $repository, UserRepository $userRepository)
  {
    $this->repository = $repository;
    $this->userRepository = $userRepository;
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
    $user = $this->userRepository->fetchUserById($userId);

    if($user){
      $classId = $_GET['class'];
      $class = $this->repository->fetchClass($classId);
      $exams = $this->repository->fetchClassExams($classId);

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
