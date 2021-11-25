<?php

  // VP & DH
  
  namespace Classes;

  use Classes\ClassesRepository;

  class ClassesController
  {
    private $repository;

    //Übergibt das Repository vom Container
    //(DH)
    public function __construct(ClassesRepository $repository)
    {
      $this->repository = $repository;
    }

    private function render($view, $content)
    {
      $classes = $content['classes'];
      $favoriteClasses = $content['favoriteClasses'];
      $twig = $content['twig'];
      $loginState = $content['loginState'];

      include "./templates/php/{$view}.php";
    }

    public function queryClass($data, $action, &$duplicate = false, &$data_id = -1) {
      return $this->repository->queryClass($data, $action, $duplicate, $data_id);
    }

    //Öffnet die Übersichtsseite der Klassen (Für Lehrer/Administratoren)
    //(DH)
    public function index($tpl, $twig, $loginState)
    {
      $userId = isset($_COOKIE['UserLogin']) ? $_COOKIE['UserLogin'] : false;

      //Falls es ein User ist
      if($userId){
        $favoriteClasses = $this->repository->fetchFavoriteClasses($userId);
        $classes = $this->repository->fetchClasses();

        // VP
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
