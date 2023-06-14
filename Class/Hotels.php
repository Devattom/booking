<?php


class Hotels {

    private $_idHotel;
    private $_nameHotel;
    private $_address;
    private $_picture;


    public function __construct($data = [])
    {   
        if(!empty($data)) {
            $this->hydrater($data);
        }
    }

    public function hydrater($data) {
        foreach($data as $attribut => $values) {
            $setters = 'set' . ucfirst($attribut);
            if(method_exists($this, $setters)) {
                $this->$setters($values);
            } else {
                echo 'non'.'<br>';
            }
        }
    }

    //Setters

    public function setId($id) {
        if(is_numeric($id) AND !empty($id)) {
            $this->_idHotel = (int) $id;
        }
    }

    public function setName($name) {
        if(is_string($name) AND !empty($name)) {
            $this->_nameHotel = $name;
        }
    }

    public function setAddress($address) {
        if(is_string($address) AND !empty($address)) {
            $this->_address = $address;
        }
    }

    public function setPicture($picture) {
        if(is_string($picture) AND !empty($picture)) {
            $this->_picture = PATH_IMG . $picture;
        }
    }


    //Getters

    public function getId() {
        return $this->_idHotel;
    }

    public function getName(){
        return $this->_nameHotel;
    }

    public function getAddress(){
        return $this->_address;
    }

    public function getPicture(){
        return $this->_picture;
    }
}