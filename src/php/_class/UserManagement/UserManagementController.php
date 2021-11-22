<?php
namespace UserManagement;

use UserManagement\UserManagementRepository;
use User\UserRepository;

class UserManagementController
{
    private $repository;
    private $userRepository;

    //Übergibt das Repository vom Container
    public function __construct(UserManagementRepository $repository, UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
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
        $userId = isset($_COOKIE['UserLogin']) ? $_COOKIE['UserLogin'] : false;
    
        //Falls es ein User ist
        if($userId){
            $user = $this->userRepository->fetchUserById($userId); 
            //Falls der User ein Admin ist
            if($user->is_admin == 1){
                $this->render("{$tpl}", [
                    'twig' => $twig,
                    'loginState' => $loginState
                ]);
            } 
        } else {
            header("Refresh:0; url=?page=dashboard");
            exit();
        }
    }

}

?>