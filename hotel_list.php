<?php require 'include/header.html' ;?>
<title>Booking - Liste des hôtels</title>
</head>

<?php 
    require_once ('path.php');
    require ('includeClass.php');

    try{

        $connectDb = new PDO('mysql:host=localhost; dbname=booking', 'root', '');
    
    } catch (Exception $e) {
        echo 'Erreur de connexion :' . $e;
    } 

    //connexion à la BDD
    $hotel_manager = new HotelsManager($connectDb);






?>
<body>
    <div class="wrap-listpage">
    <?php require 'include/navigation.html' ;?>

    




  <div class="list-hotel">
    <!-- Boucle dans le tableau de tous les hôtels -->
    <?php foreach($hotel_manager->getListHotels() as $k =>$hotel ) :?>
        <!-- Instanciation d'un objet hotels puis hydratation avec les valeurs récuperées de la BDD -->
        <?php $hotels = new Hotels; ?>
        <?php $hotels->hydrater($hotel); ?>
        
        
        
            <!-- Création de card en récupérant les éléments via les getters -->
            <div class="card-hotel">
                <div class="card-hotel__img"><img src="<?php echo $hotels->getPicture() ?>" alt=""></div>
                <div class="card-hotel__title">
                    <h2>Nom de l'hotel :</h2>
                    <?= $hotels->getName() ?>
                </div>
                <div class="card-hotel__address">
                    <h2>Adresse :</h2>
                    <?= $hotels->getAddress(); ?>
                </div>
                <div class="card-hotel__reserver">
                    <!-- Permet de faire passer l'Id via l'URL et ainsi sélectionner directement l'hôtel dans la liste -->
                    <a href="booking_page.php?id=<?php echo $hotels->getId() ?>">Réserver cet hôtel</a>
                </div>
            </div>
            
            <?php endforeach ?>
            
        </div>


    </div>
</body>
</html>