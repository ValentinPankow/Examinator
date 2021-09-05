<?php
namespace Foo;

use Foo\FooRepository;

class FooController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(FooRepository $repository)
    {
        $this->repository = $repository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe index()
    private function render($view, $content)
    {
        $foos = $content['foos'];
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Bars aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    // public function index($id, $tpl, $twig)
    public function index($tpl, $twig)
    {
        //Example für fetchAll (SELECT * FROM bars)
        $foos = $this->repository->fetchFoos();

        // var_dump($id);

        $this->render("{$tpl}", [
            'foos' => $foos,
            'twig' => $twig
        ]);
    }

}

?>