<?php
namespace Subjects\SubjectManagement;

use Subjects\SubjectsRepository;
use User\UserRepository;

class SubjectManagementController
{
  private $repository;
  private $userRepository;

  //Übergibt das Repository vom Container
  //(DH)
  public function __construct(SubjectsRepository $repository, UserRepository $userRepository)
  {
    $this->repository = $repository;
    $this->userRepository = $userRepository;
  }

  //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
  //(DH)
  private function render($view, $content)
  {
    $subjects = $content['subjects'];
    $twig = $content['twig'];
    $userName = $content['userName'];

    include "./templates/php/{$view}.php";
  }

  //Öffnet die Fachverwaltung (Administrator)
  //(DH)
  public function index($tpl, $twig)
  {
    $userId = 2;
    $user = $this->userRepository->fetchUserById($userId);

    if($user->is_admin == 1){
      $subjects = $this->repository->fetchSubjects();

      $this->render("{$tpl}", [
          'subjects' => $subjects,
          'userName' => $user->first_name . " " . $user->last_name,
          'twig' => $twig,
      ]);
    }else{
      header("Location: http://localhost:8000/?page=dashboard");
      exit();
    }
  }


  public function querySubject($data, $action)
  {
    return $this->repository->querySubject($data, $action);
  }

  public function fetchSubject($id)
  {
    return $this->repository->fetchSubject($id);
  }

  public function deleteSubject($id)
  {
    return $this->repository->deleteSubject($id);
  }

}

?>
