<?php
  
  namespace Classes;

  use Classes\ClassesRepository;
  use User\UserRepository;

  class ClassesController
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

    private function render($view, $content)
    {
      $classes = $content['classes'];
      $favoriteClasses = $content['favoriteClasses'];
      $twig = $content['twig'];
      $loginState = $content['loginState'];

      include "./templates/php/{$view}.php";
    }

    public function queryClass($data, $action, &$duplicate = false) {
      return $this->repository->queryClass($data, $action, $duplicate);
    }

    //Öffnet die Übersichtsseite der Klassen (Für Lehrer/Administratoren)
    //(DH)
    public function index($tpl, $twig, $loginState)
    {
      $userId = $_COOKIE['UserLogin'];

      //Falls es ein User ist
      if($userId){
        $user = $this->userRepository->fetchUserById($userId);
        $favoriteClasses = $this->repository->fetchFavoriteClasses($userId);
        $classes = $this->repository->fetchClasses();

        $this->render("{$tpl}", [
            'classes' => $classes,
            'favoriteClasses' => $favoriteClasses,
            'twig' => $twig,
            'loginState' => $loginState
        ]);
      }else{
        header("Refresh:0; url=?page=dashboard");
        exit();
      }
    }

  }
