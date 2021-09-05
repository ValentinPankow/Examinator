<?php

function autoloader($className)
{
    $className = str_replace("\\", "/", $className);
    if(file_exists("./src/{$className}.php"))
    {
        require "./src/{$className}.php";
    }
}

spl_autoload_register("autoloader");
?>