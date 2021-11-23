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
        $loginState = $content['loginState'];

        include "./templates/php/{$view}.php";
    }


    //Sucht sich alle Bars aus dem Repository(DB) heraus und übergibt Sie der render() Methode
    // public function index($id, $tpl, $twig)
    public function index($tpl, $twig, $loginState)
    {
        $userId = isset($_COOKIE['UserLogin']) ? $_COOKIE['UserLogin'] : false;
        if($userId){
            //Example für fetchAll (SELECT * FROM bars)
            $users = $this->repository->fetchUsers();

            $this->render("{$tpl}", [
                'users' => $users,
                'twig' => $twig,
                'loginState' => $loginState
            ]);
        }

    }

    public function listAccounts() {
        return $this->repository->fetchUserData();
    }

    public function queryUser($data, $action, &$duplicate = false) {
        return $this->repository->queryUser($data, $action, $duplicate);
    }

    public function fetchUserById($id) {
        return $this->repository->fetchUserById($id);
    }

    public function getUserDataById($id) {
        return $this->repository->getUserDataById($id);
    }

    public function deleteUserById($id) {
        return $this->repository->deleteUserById($id);
    }

}

?>
