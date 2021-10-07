<?php
namespace Users;

use Users\UsersRepository;

class UsersController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(UsersRepository $repository)
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



    // public function index($id, $tpl, $twig)
    public function index($tpl, $twig)
    {

        $users = $this->repository->fetchUsers();

        $this->render("{$tpl}", [
            'users' => $users,
            'twig' => $twig
        ]);
    }

}

?>
