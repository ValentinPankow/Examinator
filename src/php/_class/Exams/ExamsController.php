<?php
namespace Exams;

use Exams\ExamsRepository;

class ExamsController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(ExamsRepository $repository)
    {
        $this->repository = $repository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe index()
    private function render($view, $content)
    {
        $exams = $content['exams'];
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }

    //Sucht sich alle Bars aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    // public function index($id, $tpl, $twig)
    public function index($tpl, $twig)
    {
        //Example für fetchAll (SELECT * FROM bars)
        $exams = $this->repository->fetchExams();
        $this->render("{$tpl}", [
            'exams' => $exams,
            'twig' => $twig
        ]);
    }

}

?>
