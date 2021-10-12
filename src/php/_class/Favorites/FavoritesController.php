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
      $string = $content['string'];

      include "./templates/php/{$view}.php";
    }


    public function index($tpl, $twig)
    {
      $userId = 1;
      $is_teacher = true;

      if($is_teacher){
        //Holt sich die favorisierten Einträge des Benutzers
        $favoriteClasses = $this->classesRepository->fetchFavoriteClasses($userId);
        $favoriteSubjects = $this->subjectsRepository->fetchFavoriteSubjects($userId);

        //Holt sich die IDs von den Favoriten und setzt Sie für einen WHERE NOT IN Befehl zusammen
        $notFavoriteClassIds = $this->notFavoriteIds($favoriteClasses);
        $notFavoriteSubjectIds = $this->notFavoriteIds($favoriteSubjects);

        //Holt sich alle Einträge von den nicht Favoriten
        $classes = $this->classesRepository->fetchClassesWithoutFavorites($notFavoriteClassIds);
        $subjects = $this->subjectsRepository->fetchSubjectsWithoutFavorites($notFavoriteSubjectIds);

        //Wandelt die Einträge in Strings um. Der 2te Parameter bestimmt wie viele Spalten pro Reihe
        $favoriteClasses = $this->toRowString($favoriteClasses, 2, 'class');
        $favoriteSubjects = $this->toRowString($favoriteSubjects, 2, 'subject');
        $classes = $this->toRowString($classes, 6, 'class');
        $subjects = $this->toRowString($subjects, 6, 'subject');


        $this->render("{$tpl}", [
            'favoriteClasses' => $favoriteClasses,
            'favoriteSubjects' => $favoriteSubjects,
            'classes' => $classes,
            'subjects' => $subjects,
            'userId' => $userId,
            'twig' => $twig,
        ]);
      }
    }


    public function toRowString($content, $columnsEachRow, $type)
    {

      $contentString = "<div class='row'>";
      $size = count($content);

      for($i = 1; $i <= $size; $i++)
      {
        if($i % $columnsEachRow == 0 && $i != 0){
          $contentString .= "<div class='row'>";
        }

        $contentString .= "<div class='col-2'>
        <div class='custom-control custom-switch'>
        <input type='checkbox' class='custom-control-input' id='{$type}_{$content[$i-1]->id}'>
        <label class='custom-control-label' for='{$type}_{$content[$i-1]->id}'>{$content[$i-1]->name}</label></div></div>";

        //Falls die komplette Reihe abgeschlossen ist
        if($i % $columnsEachRow == 0 && $i != 0){
          $contentString .= "</div>";
        }
      }

      //Falls die komplette Reihe nach Ende der Schleife nicht abgeschlossen
      if($i-1 % $columnsEachRow != 0 || $i == 0){
        $contentString .= "</div>";
      }

      return $contentString;
    }


    //Holt sich die IDs von den nicht Favoriten und setzt Sie für einen WHERE NOT IN Befehl zusammen
    public function notFavoriteIds($content)
    {
      $size = count($content);
      $count = 0;
      $ids = "";

      foreach($content as $c){
        $count++;
        $ids .= "$c->id";

        if($count != $size){
          $ids .= ", ";
        }
      }
      return $ids;
    }
}



