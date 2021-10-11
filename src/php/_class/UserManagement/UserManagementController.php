<?php
namespace UserManagement;

use UserManagement\UserManagementRepository;

class UserManagementController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(UserManagementRepository $repository)
    {
        $this->repository = $repository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe index()
    private function render($view, $content)
    {
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Bars aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    // public function index($id, $tpl, $twig)
    public function index($tpl, $twig)
    {

        $this->render("{$tpl}", [
            'twig' => $twig
        ]);
    }

}

?>