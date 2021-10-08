<?php

namespace Classes;

class ClassesModel
{
    public $id;
    public $name;
    private $password;

    public function getPassword()
    {
      return $this->password;
    }
}

?>
