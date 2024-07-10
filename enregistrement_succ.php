<?php

session_start();

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['CONNECT']) || $_SESSION['CONNECT'] !== 'OK3') {
 
      
    header('Location: login.html?error=1');         
    exit();
} 
// Traitement PHP pour la modification
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"]) && $_GET["action"] == "modifier") {
    session_start();
   
    $_SESSION['CONNECT']='OK2';
    header("Location: page_modification.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Réussie</title>
    <style>
body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f5f5f5;
    font-size: 18px; /* Augmentation de la taille de la police globale */
}

.confirmation {
    text-align: center;
    background-color: white;
    padding: 40px; /* Augmentation de l'espacement intérieur */
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    width: 50%; /* Vous pouvez ajuster la largeur pour qu'elle soit plus adaptée à votre design */
    max-width: 600px; /* Assure que la boîte ne devienne pas trop large sur les grands écrans */
}

.buttons button {
    margin: 15px; /* Espacement plus large autour des boutons */
    padding: 15px 30px; /* Boutons plus grands */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 20px; /* Taille de police plus grande pour les textes des boutons */
}

#modifyBtn {
    background-color: #4CAF50;
    color: white;
}

#quitBtn {
    background-color: #f44336;
    color: white;
}


    </style>

</head>
<body>

<div class="confirmation">
    <h2>Inscription Réussie !</h2>
    <p>Vos informations ont été enregistrées avec succès.</p>
    <div class="buttons">
        <button id="modifyBtn">Modifier mes informations</button>
        <button id="quitBtn">Quitter</button>
    </div>
</div>

<script >

document.getElementById("modifyBtn").addEventListener("click", function() {
    // Rediriger vers la même page avec un paramètre pour indiquer l'exécution du script PHP
    window.location.href = window.location.pathname + "?action=modifier";
});

document.getElementById("quitBtn").onclick = function() {
    
    window.location.href = 'exit.php'; 

};


</script>

</body>
</html>
