<?php

function includeClass($className) {
    if(file_exists($file = __DIR__ . '/' .'Class/' . $className . '.php')) {
        require $file;
    }
}

spl_autoload_register('includeClass');