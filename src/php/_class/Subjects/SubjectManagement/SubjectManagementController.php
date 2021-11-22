<?php
namespace Subjects\SubjectManagement;

use Subjects\SubjectsRepository;

class SubjectManagementController
{
  private $repository;

  //Übergibt das Repository vom Container
  //(DH)
  public function __construct(SubjectsRepository $repository)
  {
    $this->repository = $repository;
  }

  //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
  //(DH)
  private function render($view, $content)
  {
    $subjects = $content['subjects'];
    $twig = $content['twig'];
    $loginState = $content['loginState'];

    include "./templates/php/{$view}.php";
  }

  //Öffnet die Fachverwaltung (Administrator)
  //(DH)
  public function index($tpl, $twig, $loginState)
  {
    $userId = isset($_COOKIE['UserLogin']) ? $_COOKIE['UserLogin'] : false;

    //Falls es ein User ist
    if($userId){
      $subjects = $this->repository->fetchSubjects();

      $this->render("{$tpl}", [
          'subjects' => $subjects,
          'loginState' => $loginState,
          'twig' => $twig,
      ]);
    }else{
      header("Refresh:0; url=?page=dashboard");
      exit();
    }
  }


  public function querySubject($data, $action, &$duplicate, &$data_id = -1)
  {
    return $this->repository->querySubject($data, $action, $duplicate, $data_id);
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
