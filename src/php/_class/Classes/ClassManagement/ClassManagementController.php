<?php
namespace Classes\ClassManagement;

use Classes\ClassesRepository;

class ClassManagementController
{
  private $repository;

  //Übergibt das Repository vom Container
  //(DH)
  public function __construct(ClassesRepository $repository)
  {
    $this->repository = $repository;
  }


  //(DH)
  private function render($view, $content)
  {
    $classes = $content['classes'];
    $twig = $content['twig'];
    $loginState = $content['loginState'];

    include "./templates/php/{$view}.php";
  }

  //Öffnet die Klassenverwaltung (Für Administratoren)
  //(DH)
  public function index($tpl, $twig, $loginState)
  {
    $userId = isset($_COOKIE['UserLogin']) ? $_COOKIE['UserLogin'] : false;   $userId = isset($_COOKIE['UserLogin']) ? $_COOKIE['UserLogin'] : false;
    
    //Falls es ein User ist
    if($userId){
      $classes = $this->repository->fetchClasses();

      $this->render("{$tpl}", [
          'classes' => $classes,
          'twig' => $twig,
          'loginState' => $loginState
      ]);
    } else {
      header("Refresh:0; url=?page=dashboard");
      exit();
    }
  }


  public function queryClass($data, $action, &$duplicate, &$data_id = -1)
  {
    return $this->repository->queryClass($data, $action, $duplicate, $data_id);
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
