<?php
session_start();

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['CONNECT']) || $_SESSION['CONNECT'] !== 'OK1') {
 
      
    header('Location: login.html?error=1');         
    exit();
} 
$apogee=$_SESSION['apogee'];


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de soumission</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <nav>
<nav id="navbar1">
    <div class="navbar-content">
        Formulaire de Candidature pour Doctorat
    </div>
</nav>

    <form id="studentForm" action="submission_page.php" method="POST">
        <!-- Auteur principal -->
        <fieldset>
            <legend>Auteur principal</legend>
            Sexe: 
            <select name="firstauthorSex">
                <option value="M">M</option>
                <option value="F">F</option>
            </select>
            Code Apogée: <input type="text" name="firstauthorApogeCode" value="<?php echo $apogee ;?>" required readonly >
            Année d'inscription: 
            <select name="firstauthorRegistrationYear">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>
            Prénom: <input type="text" name="firstauthorFirstname" required>
            Nom: <input type="text" name="firstauthorLastname" required>
            Date de naissance: <input type="date" name="firstauthorBirth" required>
        </fieldset>
    
        <!-- Zone pour ajouter des coauteurs -->
        <div id="coauthorsContainer"></div>
        <button type="button" id="addAuthorBtn">Ajouter un coauteur</button><br>
        
        
        <!-- Des champs similaires peuvent être ajoutés pour <author> -->
        
        Titre: <input type="text" name="title" required>
        Résumé (1000 caractères max): <textarea name="abstract" maxlength="1000" required></textarea>
        Mots-clés: <input type="text" name="keywords" required>
        
        Discipline: 
        <select name="disciplines">
        <option value="" disabled selected>Choisir un domaine</option>
            <option value="Math">Math</option>
            <option value="Informatique">Informatique</option>
            <option value="Biologie">Biologie</option>
            <option value="Environnement">Environnement</option>
            <option value="chimie">chimie</option>
            <option value="Physics">Physics</option>

            <!-- Ajoutez d'autres options ici -->
        </select>
        
        Spécialité: <input type="text" name="speciality" required>
        
        Thème: 
        <select name="theme">
    <option value="" disabled selected>Choisir un domaine de recherche</option>
    <option value="Algebre lineaire et ses applications">Algebre lineaire et ses applications</option>
    <option value="Calcul differentiel et integral">     Calcul differentiel et integral</option>
    <option value="Theorie des nombres et cryptographie">Theorie des nombres et cryptographie</option>
    <option value="Intelligence artificielle et apprentissage automatique">Intelligence artificielle et apprentissage automatique</option>
    <option value="Reseaux informatiques et securite">Reseaux informatiques et securite</option>
    <option value="Developpement web et mobile">Developpement web et mobile</option>
    <option value="Mecanique classique et mouvement des corps">Mecanique classique et mouvement des corps</option>
    <option value="Physique quantique et ses applications">Physique quantique et ses applications</option>
    <option value="Astrophysique et cosmologie">Astrophysique et cosmologie</option>
    <option value="Genetique et biotechnologie">Genetique et biotechnologie</option>
    <option value="Ecologie et dynamique des populations">Ecologie et dynamique des populations</option>
    <option value="Biologie cellulaire et moleculaire">Biologie cellulaire et moleculaire</option>
    <option value="Changement climatique et ses impacts">Changement climatique et ses impacts</option>
    <option value="Gestion des ressources naturelles et durabilite">Gestion des ressources naturelles et durabilite</option>
    <option value="Ecologie urbaine et gestion des dechets">Ecologie urbaine et gestion des dechets</option>
    <option value="Chimie organique synthetique">Chimie organique synthetique</option>
    <option value="Chimie inorganique avancee ">Chimie inorganique avancee </option>
    <option value="Chimie analytique et instrumentation">Chimie analytique et instrumentation</option>
   
</select>

        
        Institution: 
        <select name="institution">
        <option value="" disabled selected>Choisir institution</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
            
            <!-- Ajoutez d'autres options ici -->
        </select>
        
        Structure: 
        <select name="structureType">
        <option value="" disabled selected>Choisir Structure</option>
            <option value="laboratory">Laboratoire</option>
            <option value="group">Groupe</option>
        </select>
        
        Email (institutionnel): <input type="email" name="emailInstitutionnel">
        Email (autre): <input type="email" name="emailAutre">
        
        Téléphone: <input type="text" name="phone">
        Adresse: <input type="text" name="address">
        Ville: <input type="text" name="city">
        
        <!-- Superviseur -->
        <fieldset>
            <legend>Superviseur</legend>
            Prénom: <input type="text" name="supervisorFirstname" required>
            Nom: <input type="text" name="supervisorLastname" required>
            Email: <input type="email" name="supervisorEmail" required>
            Institution: <input type="text" name="supervisorInstitution" required>
        </fieldset>
        
        <!-- Co-superviseur -->
        <fieldset>
            <legend>Co-superviseur</legend>
            Prénom: <input type="text" name="cosupervisorFirstname">
            Nom: <input type="text" name="cosupervisorLastname">
            Email: <input type="email" name="cosupervisorEmail">
            Institution: <input type="text" name="cosupervisorInstitution">
        </fieldset>
    
        <button type="submit">Envoyer</button>
      
        <button type="button" id="quitBtn">Quitter</button>

    </form>
    <script>
       document.getElementById('addAuthorBtn').addEventListener('click', function() {
    const container = document.getElementById('coauthorsContainer');
    const authorCount = container.children.length + 1;
    const html = `
        <fieldset id="coauthor${authorCount}">
            <legend>Coauteur ${authorCount}</legend>
            Sexe: 
            <select name="coauthorSex[]">
                <option value="M">M</option>
                <option value="F">F</option>
            </select>
            Code Apogée: <input type="text" name="coauthorApogeCode[]" required>
            Année d'inscription: 
            <select name="coauthorRegistrationYear[]">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>
            Prénom: <input type="text" name="coauthorFirstname[]" required>
            Nom: <input type="text" name="coauthorLastname[]" required>
            Date de naissance: <input type="date" name="coauthorBirth[]" required>
        </fieldset>
    `;
    container.insertAdjacentHTML('beforeend', html);
});
var quitBtn = document.getElementById("quitBtn");

// Ajoute un écouteur d'événements pour le clic sur le bouton "Quitter"
quitBtn.addEventListener("click", function() {
    // Redirige l'utilisateur vers une autre page (remplacez "nom_de_la_page.html" par le nom de votre page de redirection)
    window.location.href = "exit.php";
});

        </script>
    </body>
    </html>