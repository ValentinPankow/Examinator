<?php

function autoloader($className)
{
    $className = str_replace("\\", "/", $className);
    if(file_exists("./src/php/_class/{$className}.php"))
    {
        require "./src/php/_class/{$className}.php";
    }
}

spl_autoload_register("autoloader");
?>