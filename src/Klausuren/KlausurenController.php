<?php
namespace Klausuren;

use Klausuren\KlausurenRepository;

class KlausurenController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(KlausurenRepository $repository)
    {
        $this->repository = $repository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe index()
    private function render($view, $content)
    {
        $klausuren = $content['klausuren'];
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Bars aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    // public function index($id, $tpl, $twig)
    public function index($tpl, $twig)
    {
        //Example für fetchAll (SELECT * FROM bars)
        $klausuren = $this->repository->fetchKlausuren();

        $this->render("{$tpl}", [
            'klausuren' => $klausuren,
            'twig' => $twig
        ]);
    }

}