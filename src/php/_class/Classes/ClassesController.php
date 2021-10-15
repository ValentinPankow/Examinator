<?php
namespace Classes;

use Classes\ClassesRepository;
use User\UserRepository;

class ClassesController
{
  private $repository;
  private $userRepository;

  //Übergibt das Repository vom Container
  public function __construct(ClassesRepository $repository, UserRepository $userRepository)
  {
    $this->repository = $repository;
    $this->userRepository = $userRepository;
  }

  //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
  //Beispiel siehe index()
  private function render($view, $content)
  {
    $classes = $content['classes'];
    $favoriteClasses = $content['favoriteClasses'];
    $twig = $content['twig'];
    $userName = $content['userName'];

    include "./templates/php/{$view}.php";
  }


  public function index($tpl, $twig)
  {

    //$userId = $auth->user->id;
    $userId = 2;
    $user = $this->userRepository->fetchUserById($userId);

    if($user){
      $favoriteClasses = $this->repository->fetchFavoriteClasses($userId);
      $classes = $this->repository->fetchClasses();

      $this->render("{$tpl}", [
          'classes' => $classes,
          'favoriteClasses' => $favoriteClasses,
          'userName' => $user->first_name . " " . $user->last_name,
          'twig' => $twig,
      ]);
    }else{

      //header("Location: http://localhost:8000/?page=dashboard");
      exit();
    }

  }

}

?>
