<?php

class Manager {

    protected $_db;
    //Gestion de connexion avec la BDD
    public function __construct(PDO $db) {

        $this->setDb($db);
    }

    //Setter 

    public function setDb(PDO $db) {
        $this->_db = $db;
    }

}