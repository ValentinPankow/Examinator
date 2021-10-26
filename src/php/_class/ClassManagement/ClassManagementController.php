<?php
namespace ClassManagement;

use ClassManagement\ClassManagementRepository;

class ClassManagementController
{
    private $repository;

    //Übergibt das Repository vom Container
    public function __construct(ClassManagementRepository $repository)
    {
        $this->repository = $repository;
    }

    private function render ($view, $content)
    {
        $twig = $content['twig'];

        include "./templates/php/{$view}.php";
    }

    public function index($tpl, $twig)
    {
        $this->render("{$tpl}", [
            'twig' => $twig
        ]);
    }
}

?>