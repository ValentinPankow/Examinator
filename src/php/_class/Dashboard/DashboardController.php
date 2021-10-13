<?php
namespace Dashboard;

use User\UserRepository;
use Exams\ExamsRepository;
use Classes\ClassesRepository;
use Dashboard\DashboardRepository;

class DashboardController
{
    private $userRepository;
    private $examsRepository;
    private $classesRepository;
    private $subjectRepository;

    //Übergibt das Repository vom Container (DH)
    public function __construct(UserRepository $userRepository, ExamsRepository $examsRepository, ClassesRepository $classesRepository)
    {
        $this->userRepository = $userRepository;
        $this->examsRepository = $examsRepository;
        $this->classesRepository = $classesRepository;
    }

    //(DH)
    private function render($view, $content, $login_type)
    {
        $twig = $content['twig'];
        $exams = $content['exams'];

        if($login_type == 'teacher'){
          $classes = $content['classes'];
          $user = $content['user'];
        }elseif($login_type == 'class'){
          $class = $content['class'];
        }


        include "./templates/php/{$view}.php";
    }


    //(DH)
    public function index($tpl, $twig)
    {
    //Testweise als ob eine Klasse oder Lehrer wäre. (!tpl = Klasse; tpl = Lehrer)
      if($tpl){
        $login_type = 'teacher';
        $user = $this->userRepository->fetchUserById(1);
        $exams = $this->examsRepository->fetchUserExams($user->id);
        $classes = $this->classesRepository->fetchClasses();

        $this->render("{$tpl}", [
          'twig' => $twig,
          'user' => $user,
          'classes' => $classes,
          'exams' => $exams,
          ],
          'teacher'
        );
      }else{
        $login_type = 'class';
        $class = $this->classesRepository->fetchByName('12ITa');
        $exams = $this->examsRepository->fetchClassExams($class->id);

        $this->render("{$tpl}", [
          'twig' => $twig,
          'class' => $class,
          'exams' => $exams
          ],
          $login_type);
        }
    }
}
