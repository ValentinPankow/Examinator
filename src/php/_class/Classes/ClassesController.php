<?php
namespace Classes;

use Classes\ClassesRepository;

class ClassesController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(ClassesRepository $repository)
    {
        $this->repository = $repository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe index()
    private function render($view, $content)
    {
        $classes = $content['classes'];
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Bars aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    // public function index($id, $tpl, $twig)
    public function index($tpl, $twig)
    {
        //Example für fetchAll (SELECT * FROM bars)
        $classes = $this->repository->fetchClasses();

        // var_dump($id);

        $this->render("{$tpl}", [
            'classes' => $classes,
            'twig' => $twig
        ]);
    }

}

?>