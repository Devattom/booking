<?php

class Booking {

    private $erreurs = [];
    private $_id_booking;
    private $_date_start;
    private $_date_end;
    private $_booking_date;
    private $_id_customer;
    private $_id_hotel;
    private $_id_room;


    const DATE_START_INVALIDE = 1;
    const DATE_END_INVALIDE = 2;
    const DATE_BOOKING_INVALIDE = 3;
    const DATE_START_SUP = 4;
    const DATE_CURRENT_INF = 5;
    const EMPTY_ID_HOTEL = 6;

    public function __construct(array $data = [])
    {
        $this->setDateStart($data['date_start']);
        $this->setDateEnd($data['date_end']);
        $this->setBookingDate($data['booking_date']);
        $this->setIdCustomer($data['id_customer']);
        $this->setIdHotel($data['id_hotel']);
        $this->setIdRoom($data['id_room']);
    }

    //Setters 

    public function setIdBooking(int $id){
        if(is_numeric($id) AND !empty($id)) {
            $this->_id_booking = $id;
        }
    }

    public function setDateStart(string $date) {
        list($y, $m, $d) = explode('-', $date);
        if(checkdate($m, $d, $y)) {
            $this->_date_start = $date;
        } else {
            $this->erreurs[] = self::DATE_START_INVALIDE;
        }
    }

    public function setDateEnd(string $date) {
       list($y, $m, $d) = explode('-', $date);
        if(checkdate($m, $d, $y)) {
            $this->_date_end = $date;
        } else {
            $this->erreurs[] = self::DATE_END_INVALIDE;
        }
    }
    

    public function setBookingDate(string $date) {
        list($y, $m, $d) = explode('-', $date);
        if(checkdate($m, $d, $y)) {
            $this->_booking_date = $date;
        } else {
            $this->erreurs[] = self::DATE_BOOKING_INVALIDE;
        }
    }
    

    public function setIdCustomer(int $id) {
        if(is_numeric($id) AND !empty($id)) {
            $this->_id_customer = $id;
        }
    }

    public function setIdHotel(int $id) {
        if(is_numeric($id) AND !empty($id)) {
            $this->_id_hotel = $id;
        }
    }

    public function setIdRoom(int $id) {
        if(is_numeric($id) AND !empty($id)) {
            $this->_id_room = $id;
        }
    }

    //Getters 

    public function getIdBooking() {
        return $this->_id_booking;
    }

    public function getDateStart() {
        return $this->_date_start;
    }

    public function getDateEnd() {
        return $this->_date_end;
    }

    public function getBookingDate() {
        return $this->_booking_date;
    }

    public function getIdCustomer() {
        return $this->_id_customer;
    }

    public function getIdHotel() {
        return $this->_id_hotel;
    }

    public function getIdRoom() {
        return $this->_id_room;
    }

    public function getErreur() {
        return $this->erreurs;
    }

    

    public function isBookingValid() {
        return !(empty($this->_date_start) || empty($this->_date_end) || empty($this->_id_customer) || empty($this->_id_hotel));
    }

    public function isDateValid($current_date) {
        if($this->getDateStart() >= $this->getDateEnd()) {
            $this->erreurs[] = self::DATE_START_SUP;
            return false;
        } 
        elseif($this->getDateStart() < $current_date) {
            $this->erreurs[] = self::DATE_CURRENT_INF;
            return false;
        } else {
            return true;
        }
    }

}