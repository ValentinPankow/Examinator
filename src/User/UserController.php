<?php
namespace User;

use User\UserRepository;

class UserController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe index()
    private function render($view, $content)
    {
        $users = $content['users'];
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Bars aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    // public function index($id, $tpl, $twig)
    public function index($tpl, $twig)
    {
        //Example für fetchAll (SELECT * FROM bars)
        $users = $this->repository->fetchUsers();

        // var_dump($id);

        $this->render("{$tpl}", [
            'users' => $users,
            'twig' => $twig
        ]);
    }

}

?>