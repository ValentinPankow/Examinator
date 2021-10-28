<?php
namespace Favorites;

use Classes\ClassesRepository;
use Subjects\SubjectsRepository;
use User\UserRepository;

class FavoritesController
{
    private $classesRepository;
    private $subjectsRepository;

    //Übergibt das Repository vom Container (DH)
    public function __construct(ClassesRepository $classesRepository, SubjectsRepository $subjectsRepository, UserRepository $userRepository)
    {
      $this->classesRepository = $classesRepository;
      $this->subjectsRepository = $subjectsRepository;
      $this->userRepository = $userRepository;
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

    //(DH)
    public function index($tpl, $twig)
    {
      $userId = 2;
      $user = $this->userRepository->fetchUserById($userId);

      if($user->is_teacher || $user->is_admin){
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
        //Der 3te Parameter wie der Präfix der Checkbox heißt & der 4te Parameter ob es Favorit/en sind
        $favoriteClasses = $this->toRowString($favoriteClasses, 'class', true);
        $favoriteSubjects = $this->toRowString($favoriteSubjects, 'subject', true);
        $classes = $this->toRowString($classes, 'class', false);
        $subjects = $this->toRowString($subjects, 'subject', false);


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

    //Gibt die Favoriten als HTML Checkboxen aus. type = class/subject - favorite = true/false (Um die Checkboxen als checked zu markieren) (DH)
    public function toRowString($content, $type, $favorite)
    {
      //Startet die erste Reihe
      $contentString = "<div class='row'>";
      $size = count($content);

      //Checkbox setzen falls es bereits Favoriten sind und Breakpoints anpassen
      if($favorite == true){
        $checked = "checked";
        $breakpoint = [
          'lg' => 4,
          'md' => 6,
          'sm' => 6,
          'xs' => 12
        ];
      }else{
        $checked = "";
        $breakpoint = [
          'lg' => 3,
          'md' => 4,
          'sm' => 6,
          'xs' => 6
        ];
      }

      //Für jeden vorhandenen Eintrag [...]
      for($i = 1; $i <= $size; $i++)
      {
        //[...] eine Checkbox erstellen
        $contentString .= "<div class='col-lg-{$breakpoint['lg']} col-md-{$breakpoint['md']} col-sm-{$breakpoint['sm']} col-xs-{$breakpoint['xs']}'><div class='custom-control custom-switch'>
        <input type='checkbox' class='custom-control-input' id='{$type}_{$content[$i-1]->id}'{$checked}>
        <label class='custom-control-label pr-4' for='{$type}_{$content[$i-1]->id}'>{$content[$i-1]->name}</label></div></div>";
      }

      $contentString .= "</div>";

      return $contentString;
    }

    //Holt sich die IDs von den nicht Favoriten und setzt Sie für einen WHERE NOT IN Befehl zusammen (DH)
    public function notFavoriteIds($content)
    {
      $size = count($content);
      $count = 0;
      $ids = "";

      foreach($content as $c){
        $count++;
        $ids .= "$c->id";

        if($count != $size){
          $ids .= ",";
        }
      }
      return $ids;
    }
}



