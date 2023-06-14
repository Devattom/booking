<?php

class BookingManager extends Manager {

    public function insertCustomer(Customer $customer) {
        $sql = 'INSERT INTO booking.customer (name, email) VALUES (:name, :email)';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindValue(':name', $customer->getName());
        $stmt->bindValue(':email', $customer->getEmail());

        $stmt->execute();
    }

    public function selectCustomer($name, $email) {
        $sql = 'SELECT id_customer FROM booking.customer WHERE name = :name AND email = :email';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_COLUMN, PDO::PARAM_INT);

        return (int) $result;
    }

    public function isEmailCustomerExists($email) {
        $sql = 'SELECT * FROM booking.customer WHERE email = :email';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindValue('email', $email);
        $stmt->execute();

        $result = $stmt->rowCount();

        return $result;
    }

    public function selectAllRoom($id_hotel) {
        $sql = 'SELECT id_room FROM rooms WHERE id_hotel = :id_hotel';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':id_hotel', $id_hotel);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $result;
    }

    public function selectBusyRooms($date, $id_hotel) {
        $sql = 'SELECT id_room FROM booking WHERE date_start_book <= :date AND date_end_book > :date AND id_hotel = :id_hotel';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindValue(':date',  $date);
        $stmt->bindValue(':id_hotel',  $id_hotel);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $result;

    }

    public function insertBooking(Booking $booking) {
        $sql = 'INSERT INTO booking.booking (date_start_book, date_end_book, date_create_book, id_customer, id_hotel, id_room) VALUES (:date_start, :date_end, :booking_date, :id_customer, :id_hotel, :id_room)';

        $stmt = $this->_db->prepare($sql);
        $stmt->bindValue(':date_start', $booking->getDateStart());
        $stmt->bindValue(':date_end', $booking->getDateEnd());
        $stmt->bindValue(':booking_date', $booking->getBookingDate());
        $stmt->bindValue(':id_customer', $booking->getIdCustomer());
        $stmt->bindValue(':id_hotel', $booking->getIdHotel());
        $stmt->bindValue(':id_room', $booking->getIdRoom());

        $stmt->execute();
    }


}