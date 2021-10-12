<?php
namespace Favorites;

use Classes\ClassesRepository;
use Subjects\SubjectsRepository;

class FavoritesController
{
    private $classesRepository;
    private $subjectsRepository;

    //Übergibt das Repository vom Container
    public function __construct(ClassesRepository $classesRepository, SubjectsRepository $subjectsRepository)
    {
        $this->classesRepository = $classesRepository;
        $this->subjectsRepository = $subjectsRepository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    private function render($view, $content)
    {
        $favoriteClasses = $content['favoriteClasses'];
        $favoriteSubjects = $content['favoriteSubjects'];
        $classes = $content['classes'];
        $subjects = $content['subjects'];
        $userId = $content['userId'];
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }


    public function index($tpl, $twig)
    {
        $userId = 1;
        $is_teacher = true;

        if($is_teacher){
          $favoriteClasses = $this->classesRepository->fetchFavoriteClasses($userId);
          $favoriteSubjects = $this->subjectsRepository->fetchFavoriteSubjects($userId);

          $size = count($favoriteClasses);
          $count = 0;
          $classIds = "";

          foreach($favoriteClasses as $favoriteClass){
            $count++;
            $classIds .= "$favoriteClass->id";

            if($count != $size){
              $classIds .= ", ";
            }
          }

          $size = count($favoriteSubjects);
          $count = 0;

          $subjectIds = "";
          foreach($favoriteSubjects as $favoriteSubject){
            $count++;
            $subjectIds .= "$favoriteSubject->id";

            if($count != $size){
              $subjectIds .= ", ";
            }
          }

          $classes = $this->classesRepository->fetchClassesWithoutFavorites($classIds);
          $subjects = $this->subjectsRepository->fetchSubjectsWithoutFavorites($subjectIds);

          $this->render("{$tpl}", [
              'favoriteClasses' => $favoriteClasses,
              'favoriteSubjects' => $favoriteSubjects,
              'classes' => $classes,
              'subjects' => $subjects,
              'userId' => $userId,
              'twig' => $twig
          ]);
        }
    }

}

?>
