<?php
namespace Login;

use Login\LoginRepository;
use User\UserRepository;

class LoginController
{
    private $repository;
    private $userRepository;

    //Übergibt das Repository vom Container
    public function __construct(LoginRepository $repository, UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    //Rendert den Inhalt, hierzu bekommt die Methode den Dateipfad von view Ordner bis zum Dateinamen der View selbst und dem übergebenen Content
    //Beispiel siehe index()
    private function render($view, $content)
    {
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }

    public function getUserByMail($mail) {
        return $this->userRepository->fetchUserByMail($mail);
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