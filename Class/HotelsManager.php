<?php

class HotelsManager extends Manager {

    

    //select function
        //récupère la liste des hôtel présent dans la base
    public function getListHotels() {
        $sql = 'SELECT * FROM booking.hotel';
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $list_hotels = $stmt->fetchAll();

        $stmt->closeCursor();

        return $list_hotels;
    }

    //récupère un nom d'hôtel en fonction de l'ID.
    public function getHotelName($id) {
        $sql = 'SELECT name FROM booking.hotel WHERE id = :id';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam('id', $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_COLUMN);
        return $result;
    }
}

