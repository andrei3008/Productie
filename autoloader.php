<?php
// error_reporting(E_ALL);
include 'includes/_db.inc.php';
error_reporting(0);

function entity_loader($class){
    if(file_exists(ROOT.'/classes/Entityes/'.$class.'.php')) {
        require_once ROOT . '/classes/Entityes/' . $class . '.php';
    }
}

function factory_loader($class){
    if(file_exists(ROOT.'/classes/Factoryes/'.$class.'.php')) {
        require_once ROOT . '/classes/Factoryes/' . $class . '.php';
    }
}

function common_classes($class){
    if(file_exists(ROOT.'/classes/'.$class.'.php')) {
        require_once ROOT . '/classes/' . $class . '.php';
    }
}

function mapper_classes($class){
    if(file_exists(ROOT.'/classes/Mappers/'.$class.'.php')) {
        require_once ROOT . '/classes/Mappers/' . $class . '.php';
    }
}


function connection_classes($class){
    if(file_exists(ROOT.'/includes/'.$class.'.php')) {
        require_once ROOT . '/includes/' . $class . '.php';
    }
}


spl_autoload_register('mapper_classes');
spl_autoload_register('connection_classes');
spl_autoload_register('entity_loader');
spl_autoload_register('factory_loader');
spl_autoload_register('common_classes');

?>