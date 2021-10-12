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

    public function listAccounts() {
        return $this->repository->fetchUserData();
    }

    public function queryUser($data, $action, &$duplicate = false) {
        return $this->repository->queryUser($data, $action, $duplicate);
    }

    public function fetchUserById($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE `id` = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, "User\\UserModel");
        $content = $query->fetch(PDO::FETCH_CLASS);

        return $content;
    }

}

?>