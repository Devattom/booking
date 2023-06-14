<?php
class Customer {

    private $_id_customer;
    private $_name;
    private $_email;
    private $erreurs = [];

    const NOM_INVALIDE = 1;
    const EMAIL_INVALIDE = 2;


    public function __construct($name, $email)
    {

        $this->SetName($name);
        $this->SetEmail($email);
    }

    //Setters 

    public function SetIdCustomer($id) {
        if(is_numeric($id) AND !empty($id)) {
            $this->_id_customer = $id;
        }
    }

    public function SetName($name) {
        if(!preg_match('/[a-zA-Z]/', $name) OR empty($name)) {
            $this->erreurs[] = self::NOM_INVALIDE;
        } else {
            $this->_name = htmlspecialchars($name);
        }
    }

    public function SetEmail($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_email = htmlspecialchars($email);
        } else {
            $this->erreurs[] = self::EMAIL_INVALIDE;
        }
    }

    //Getters

    public function getIdCustomer() {
        return $this->_id_customer;
    }

    public function getName() {
        return $this->_name;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function getErreur() {
        return $this->erreurs;
    }

    public function isValidCustomer() {

        return !(empty($this->_name) || empty($this->_email));
    }
}