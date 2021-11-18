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
    $twig = $content['twig'];
    $userName = $content['userName'];

    include "./templates/php/{$view}.php";
  }

  //Öffnet die Übersicht einer einzelnen Klasse (Für Lehrer/Administratoren)
  //(DH)
  public function index($tpl, $twig)
  {
    $userId = 2;
    $user = $this->userRepository->fetchUserById($userId);

    if($user){
      $classId = $_GET['class'];
      $class = $this->repository->fetchClass($classId);

      $this->render("{$tpl}", [
          'class' => $class,
          'userName' => $user->first_name . " " . $user->last_name,
          'twig' => $twig,
      ]);
    } else {
      header("Location: http://localhost:8000/?page=dashboard");
    }
  }

}
