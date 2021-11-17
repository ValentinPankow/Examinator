<?php

    namespace Subjects;

    use Subjects\SubjectsRepository;

    class SubjectsController
    {
        private $repository;

        //Übergibt das Repository vom Container
        public function __construct(SubjectsRepository $repository)
        {
            $this->repository = $repository;
        }

        //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
        //Beispiel siehe index()
        private function render($view, $content)
        {
            $twig = $content['twig'];
            $loginState = $content['loginState'];

            include "./templates/php/{$view}.php";
        }

        //Sucht sich alle Bars aus dem Repository(DB) heraus und übergibt Sie der render() Methode
        // public function index($id, $tpl, $twig)
        public function index($tpl, $twig, $loginState)
        {
            $this->render("{$tpl}", [
                'twig' => $twig,
                'loginState' => $loginState
            ]);
        }

    }
