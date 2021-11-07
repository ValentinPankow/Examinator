<?php
namespace ClassManagement;

use Classes\ClassesRepository;
use User\UserRepository;

class ClassManagementController
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
  //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
  private function render($view, $content)
  {
    $classes = $content['classes'];
    $twig = $content['twig'];
    $userName = $content['userName'];

    include "./templates/php/{$view}.php";
  }

  //(DH)
  public function index($tpl, $twig)
  {
    $userId = 2;
    $user = $this->userRepository->fetchUserById($userId);

    if($user->is_admin == 1){
      $classes = $this->repository->fetchClasses();

      $this->render("{$tpl}", [
          'classes' => $classes,
          'userName' => $user->first_name . " " . $user->last_name,
          'twig' => $twig,
      ]);
    }else{
      header("Location: http://localhost:8000/?page=dashboard");
      exit();
    }
  }


  public function queryClass($data, $action)
  {
    return $this->repository->queryClass($data, $action);
  }

  public function fetchClass($id)
  {
    return $this->repository->fetchClass($id);
  }

  public function deleteExam($id)
  {
    return $this->repository->deleteExam($id);   
  }

}

?>
