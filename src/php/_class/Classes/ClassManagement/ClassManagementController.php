<?php
namespace Classes\ClassManagement;

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
  private function render($view, $content)
  {
    $classes = $content['classes'];
    $twig = $content['twig'];
    $userName = $content['userName'];

    include "./templates/php/{$view}.php";
  }

  //Öffnet die Klassenverwaltung (Für Administratoren)
  //(DH)
  public function index($tpl, $twig)
  {
    $userId = $_COOKIE['UserLogin'];
    $user = $this->userRepository->fetchUserById($userId);

    if($user->is_admin == 1){
      $classes = $this->repository->fetchClasses();

      $this->render("{$tpl}", [
          'classes' => $classes,
          'userName' => $user->first_name . " " . $user->last_name,
          'twig' => $twig,
      ]);
    }else{
      header("Refresh:0; url=?page=dashboard");
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

  public function deleteClass($id)
  {
    return $this->repository->deleteClass($id);
  }

}

?>
