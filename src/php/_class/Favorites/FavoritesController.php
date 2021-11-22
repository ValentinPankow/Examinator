<?php
namespace Favorites;

use Classes\ClassesRepository;
use Subjects\SubjectsRepository;
use User\UserRepository;

class FavoritesController
{
  private $classesRepository;
  private $subjectsRepository;
  private $userRepository;

  //Übergibt die Repositorys vom Container
  //(DH)
  public function __construct(ClassesRepository $classesRepository, SubjectsRepository $subjectsRepository, UserRepository $userRepository)
  {
    $this->classesRepository = $classesRepository;
    $this->subjectsRepository = $subjectsRepository;
    $this->userRepository = $userRepository;
  }

  //(DH)
  private function render($view, $content)
  {
    $favoriteClasses = $content['favoriteClasses'];
    $favoriteSubjects = $content['favoriteSubjects'];
    $classes = $content['classes'];
    $subjects = $content['subjects'];
    $userId = $content['userId'];
    $twig = $content['twig'];
    $loginState = $content['loginState'];

    include "./templates/php/{$view}.php";
  }

  //Lädt die Favoriten Übersicht
  //(DH)
  public function index($tpl, $twig, $loginState)
  {
    $userId = 2;
    $user = $this->userRepository->fetchUserById($userId);

    if($user){
      //Holt sich die favorisierten Einträge des Benutzers
      $favoriteClasses = $this->classesRepository->fetchFavoriteClasses($user->id);
      $favoriteSubjects = $this->subjectsRepository->fetchFavoriteSubjects($user->id);

      //Falls es Favoriten Klassen gibt [...]
      if($favoriteClasses){
        //[...] suche die IDs heraus um damit/danach alle Klassen herauszusuchen, die keine Favoriten sind
        $notFavoriteClassIds = $this->notFavoriteIds($favoriteClasses);
        $classes = $this->classesRepository->fetchClassesWithoutFavorites($notFavoriteClassIds);
        //Falls es keine Favoriten gibt, gebe die komplette Klassenliste aus
      } else {
        $classes = $this->classesRepository->fetchClasses();
      }

      //Falls es Favoriten Fächer gibt [...]
      if($favoriteSubjects){
        //[...] suche die IDs heraus um damit/danach alle Fächer herauszusuchen, die keine Favoriten sind
        $notFavoriteSubjectIds = $this->notFavoriteIds($favoriteSubjects);
        $subjects = $this->subjectsRepository->fetchSubjectsWithoutFavorites($notFavoriteSubjectIds);
        //Falls es keine Favoriten gibt, gebe die komplette Fächerliste aus
      } else {
        $subjects = $this->subjectsRepository->fetchSubjects();
      }

      //Wandelt die Einträge in Strings um. Param1 = Objekte, Param2 = Typ, Param3 = Favorit True/False
      $favoriteClasses = $this->toRowString($favoriteClasses, 'class', true) == "<div class='row'></div>" ? "<p class='mb-0'>Keine Einträge vorhanden</p>" : $this->toRowString($favoriteClasses, 'class', true);
      $favoriteSubjects = $this->toRowString($favoriteSubjects, 'subject', true) == "<div class='row'></div>" ? "<p class='mb-0'>Keine Einträge vorhanden</p>" : $this->toRowString($favoriteSubjects, 'subject', true);

      $classes = $this->toRowString($classes, 'class', false) == "<div class='row'></div>" ? "<p class='mb-0'>Keine Einträge vorhanden</p>" : $this->toRowString($classes, 'class', false);
      $subjects = $this->toRowString($subjects, 'subject', false) == "<div class='row'></div>" ? "<p class='mb-0'>Keine Einträge vorhanden</p>" : $this->toRowString($subjects, 'subject', false);

      $this->render("{$tpl}", [
        'favoriteClasses' => $favoriteClasses,
        'favoriteSubjects' => $favoriteSubjects,
        'classes' => $classes,
        'subjects' => $subjects,
        'userId' => $userId,
        'twig' => $twig,
        'loginState' => $loginState
      ]);
    } else {
      header("Refresh:0; url=?page=dashboard");
      exit();
    }
  }

  //Gibt die Favoriten als HTML Checkboxen aus. type = class/subject - favorite = true/false (Um die Checkboxen als checked zu markieren) (DH)
  public function toRowString($content, $type, $favorite)
  {
    //Startet die erste Reihe
    $contentString = "<div class='row'>";
    $size = count($content);

    //Checkbox setzen falls es bereits Favoriten sind und Breakpoints anpassen
    if($favorite){
      $checked = "checked";
      $breakpoint = [
        'xl' => 4,
        'lg' => 6,
        'md' => 6,
        'sm' => 12,
        'xs' => 12
      ];
    }else{
      $checked = "";
      $breakpoint = [
        'xl' => 4,
        'lg' => 6,
        'md' => 6,
        'sm' => 12,
        'xs' => 12
      ];
    }

    //Für jeden vorhandenen Eintrag [...]
    for($i = 1; $i <= $size; $i++)
    {
      //[...] eine Checkbox erstellen
      $contentString .= "
        <div class='col-lg-{$breakpoint['lg']} col-md-{$breakpoint['md']} col-sm-{$breakpoint['sm']} col-xs-{$breakpoint['xs']}'><div class='card card-primary p-1 border border-primary'>
          <div class='custom-control custom-switch text-center'>
            <input type='checkbox' class='custom-control-input' name='{$type}_{$content[$i-1]->id}' id='{$type}_{$content[$i-1]->id}' value='{$content[$i-1]->id}' {$checked}>
            <label class='custom-control-label pr-4' for='{$type}_{$content[$i-1]->id}'>{$content[$i-1]->name}</label>
          </div>
        </div>
      </div>";
    }
    $contentString .= "</div>";

    return $contentString;
  }

  //Holt sich die IDs von den nicht Favoriten und setzt Sie für einen WHERE NOT IN Befehl zusammen
  //(DH)
  public function notFavoriteIds($content)
  {
    $size = count($content);
    $count = 0;
    $ids = "";

    foreach($content as $c){
      $count++;
      $ids .= $c->id;

      if($count != $size){
        $ids .= ",";
      }
    }

    return $ids;
  }

  public function desert($type, $typeIds, $userId)
  {
    if($type == 'classes'){
      $result = $this->classesRepository->desert($typeIds, $userId);
    } elseif($type == 'subjects'){
      $result = $this->subjectsRepository->desert($typeIds, $userId);
    }

    return $result;
  }
}



