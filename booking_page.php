<?php require 'include/header.html' ;?>
<title>Booking - page de réservation</title>
</head>

<?php 
    require_once ('path.php');
    require ('includeClass.php');

   

    //connexion à la BDD
    try{
        $connectDb = new PDO('mysql:host=localhost; dbname=booking', 'root', '');
    
    } catch (Exception $e) {
        echo 'Erreur de connexion :' . $e;
    } 

    
    //Si le client a cliqué sur le lien directement dans la liste des hôtels selection de l'id de l'hotel pour affichage directement dans le formulaire
    $hotel_manager = new HotelsManager($connectDb);
    if(isset($_GET['id'])) {      
        $name_hotel = $hotel_manager->getHotelName($_GET['id']);
    }

    //Déclaration de la date du jour
    $today = date('Y-m-d');


    if(isset($_POST['reserver'])) {
        
        $name_customer = $_POST['name'];
        $email_customer = $_POST['email'];
        $id_hotel = $_POST['id_hotel'];
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
        $current_date = $_POST['current_date'];

        //Instanciation des objets customer et manager
        $customer = new Customer($name_customer, $email_customer);
        $manager = new BookingManager($connectDb);
        
        //Vérification si il existe déjà l'email dans la BDD. Nous allons autoriser l'ajout de plusieurs noms identiques dans la BDD mais un mail unique pour éviter les doublons de client.
        $check_mail = $manager->isEmailCustomerExists($email_customer);
        
        //On check si les informations du client sont ok et on vérifie que le mail n'existe pas.
        if($customer->isValidCustomer()) {
            //si le mail n'existe pas on insert le client dans la BDD
            if($check_mail === 0){
                $manager->insertCustomer($customer);
            }
        //Sinon on peuple le tableau avec les erreurs définies dans la classe
        } else {
            $erreurs_customer = $customer->getErreur();
        }
        
        //Selection des chambres occupées sur la période pour cet hôtel.
        $busy_room = $manager->selectBusyRooms($date_start, $id_hotel);
        //Selection de toutes les chambres de l'hôtel.
        $all_rooms = $manager->selectAllRoom($id_hotel);
        //Création d'un tableau rempli avec les chambres libres pour la période demandée.
        $free_rooms = array_values(array_diff($all_rooms, $busy_room));

        //Si il y a au moins une chambre de libre on créé l'objet booking avec les valeurs renseignées par le client
        if(!empty($free_rooms)) {
            
            $booking = new Booking(
                [
                    'date_start' =>(string) $date_start,
                    'date_end' => (string) $date_end,
                    'booking_date' => (string) $current_date,
                    'id_customer' => (int) $manager->selectCustomer($name_customer, $email_customer),
                    'id_hotel' => (int) $id_hotel,
                    'id_room' => (int) $free_rooms[0] //la 1ère chambre disponible sera affectée à la réservation
    
                ]
                );

                //Vérification des données et de la cohérence des dates.
                if($booking->isBookingValid() AND $booking->isDateValid($current_date)) {
                    $manager->insertBooking($booking); //si ok inerstion des données puis affichage d'un texte de confirmation.
                    $success_txt = 'Votre réservation a bien été prise en compte';
                } else {
                    $erreurs_booking = $booking->getErreur(); //sinon on rempli le tableau avec les erreurs rencontrées.
                }
        } else {
            //Si aucune chambre de disponible pour la période selectionnée, affichage d'un message d'erreur. 
            $messages [] = 'Il n\'y a plus de chambre disponible pour la date selectionnée';
        }

        
            
       

        if(isset($erreurs_customer)) {
            if(in_array(Customer::NOM_INVALIDE, $erreurs_customer)) {
                $messages [] =  'Nom invalide';
            }
            if(in_array(Customer::EMAIL_INVALIDE, $erreurs_customer)) {
                $messages [] =  'Email non valide';
            }
        }

        if(isset($erreurs_booking)) {
            if(in_array(Booking::DATE_BOOKING_INVALIDE, $erreurs_booking)) {
                $messages [] = 'Format de date non valide';
            }
            if(in_array(Booking::DATE_END_INVALIDE, $erreurs_booking)) {
                $messages [] = 'Format de date non valide';
            }
            if(in_array(Booking::DATE_START_INVALIDE, $erreurs_booking)) {
                $messages [] = 'Format de date non valide';
            }
            if(in_array(Booking::DATE_START_SUP, $erreurs_booking)){
                $messages [] = 'La date d\'arrivée ne peut pas être supérieur à la date de départ'; 
            }
            if(in_array(Booking::DATE_CURRENT_INF, $erreurs_booking)){
                $messages [] = 'La date de réservation doit être égale ou supérieur à la date du jour'; 
            }
            if(in_array(Booking::EMPTY_ID_HOTEL, $erreurs_booking)){
                $messages [] = 'Veuillez selectionner un hôtel'; 
            }

        } 
    
    }

    

?>

<body>
<?php require 'include/navigation.html' ;?>

<div class="booking">
    <h1>Formulaire de réservation</h1>
    <form action="" method="post" class="booking-form">
        
    <?php if(isset($messages)) :?>     
        <div class="error_txt">
            <?php foreach($messages as $message) :?>
            <?php echo $message .'<br>'; ?>
            <?php endforeach; ?>   
        </div>
    <?php endif; ?>

    <?php if(isset($success_txt)) : ?>
        <div class="success_txt">
            <?= $success_txt; ?>
        </div>
    <?php endif; ?>


        <div class="booking-form-group">
            <label for="name">Votre nom : </label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="booking-form-group">
            <label for="email">Votre Email : </label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="booking-form-group">
            <label for="id_hotel">Choisissez votre Hôtel : </label>
            <select name="id_hotel" id="id_hotel" required>
                <option value="">Choisissez votre hôtel</option>
                <?php foreach($hotel_manager->getListHotels() as $k =>$hotel ) :?>
                <?php $hotels = new Hotels; ?>
                <?php $hotels->hydrater($hotel); ?>
                <option value="<?php echo $hotels->getId() ?>" <?php if(isset($name_hotel) AND $name_hotel == $hotels->getName()) echo 'selected' ?> ><?php echo $hotels->getName() ?></option>
                <?php endforeach ; ?>
            </select>
        </div>

        <div class="booking-form-group">
            <label for="date_start">Votre date d'arrivée :</label>
            <input type="date" id="date_start" name="date_start" required>
        </div>

        <div class="booking-form-group">
            <label for="date_end">Votre date de départ :</label>
            <input type="date" id="date_end" name="date_end" required>
        </div>

        <input type="hidden" name="current_date" value="<?php echo $today ?>">

        <input class="booking-form-submit" type="submit" name="reserver" value="Réserver">

    </form>

</div>
</body>
</html>