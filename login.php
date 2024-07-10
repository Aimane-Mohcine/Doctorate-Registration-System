<?php

session_start(); // Démarrer la session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "xml_projet";


    $conn = new mysqli($servername, $username, $password, $dbname);
    // Vérifier la connexion
    if ($conn->connect_error) {
         die("La connexion a échoué : " . $conn->connect_error);
    }

        // Récupérer les données du formulaire
    $apogee = $_POST['apogee'];
    $cin = $_POST['cin'];

        // Requête SQL pour vérifier les informations d'identification
        $sql = "SELECT * FROM Etudiants WHERE code_apogee='$apogee' AND cin='$cin'";
        $result = $conn->query($sql);
      
       
        if ($result->num_rows > 0) {
        // Utilisateur trouvé
        $row = $result->fetch_assoc();
        if ($row['premiere_connexion']) {
            $_SESSION['apogee']=$apogee;

            $_SESSION['CONNECT'] = 'OK1';

          //   Rediriger vers la page de formulaire pour la première connexion
           header("Location: formulaire_premiere_connexion.php");
     
        } else {
           

            // Sinon, rediriger vers la page de modification
            $_SESSION['CONNECT'] = 'OK2';
            $_SESSION['apogee']=$apogee;
            header("Location: page_modification.php");
        
        } }else {
        // Aucun utilisateur trouvé, afficher une alerte
    
    header('Location: login.html?error=1');         
        }

        $conn->close();


?>