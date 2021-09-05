<?php
namespace Bar;

use Bar\BarRepository;

class BarController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(BarRepository $repository)
    {
        $this->repository = $repository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe index()
    private function render($view, $content)
    {
        $bars = $content['bars'];
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Bars aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    public function index($tpl, $twig)
    {
        //Example für fetchAll (SELECT * FROM bars)
        $bars = $this->repository->fetchBars();

        $this->render("{$tpl}", [
            'bars' => $bars,
            'twig' => $twig
        ]);
    }

}

?>