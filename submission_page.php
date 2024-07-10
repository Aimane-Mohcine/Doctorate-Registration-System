<?php
// Chemin vers le fichier XML
$filePath = 'submissions.xml';
//connection a la base de donnee
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "xml_projet";

$conn = new mysqli($servername, $username, $password, $dbname);
// Vérifier la connexion
if ($conn->connect_error) {
     die("La connexion a échoué : " . $conn->connect_error);
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Vérifiez si le fichier existe déjà
    if (file_exists($filePath)) {
        // Chargez le fichier existant
        $xml = simplexml_load_file($filePath);
        // Pas besoin d'ajouter un nouvel élément submissions ici, on ajoute directement submission
    } else {
        // Créez un nouveau fichier XML si non existant
        $xml = new SimpleXMLElement('<submissions/>');
    }
    
    // Ajoutez un nouvel élément 'submission' à l'élément racine 'submissions'
    $submission = $xml->addChild('submission');

    // Ajoutez l'auteur principal
    $firstAuthor = $submission->addChild('firstauthor');
    $firstAuthor->addAttribute('sex', $_POST['firstauthorSex']);
    $firstAuthor->addAttribute('ApogeCode', $_POST['firstauthorApogeCode']);
    $firstAuthor->addAttribute('registrationYear', $_POST['firstauthorRegistrationYear']);
    $firstAuthor->addChild('firstname', $_POST['firstauthorFirstname']);
    $firstAuthor->addChild('lastname', $_POST['firstauthorLastname']);
    $firstAuthor->addChild('birth', $_POST['firstauthorBirth']);

    // Supposons que vous avez un mécanisme pour soumettre plusieurs auteurs (coauteurs)
    if (isset($_POST['coauthorFirstname']) && is_array($_POST['coauthorFirstname'])) {
        // Boucle sur chaque coauteur
        for ($i = 0; $i < count($_POST['coauthorFirstname']); $i++) {
            $coauthor = $submission->addChild('author');
            $coauthor->addAttribute('sex', $_POST['coauthorSex'][$i]);
            $coauthor->addAttribute('ApogeCode', $_POST['coauthorApogeCode'][$i]);
            $coauthor->addAttribute('registrationYear', $_POST['coauthorRegistrationYear'][$i]);
            $coauthor->addChild('firstname', $_POST['coauthorFirstname'][$i]);
            $coauthor->addChild('lastname', $_POST['coauthorLastname'][$i]);
            $coauthor->addChild('birth', $_POST['coauthorBirth'][$i]);
        }
    }

    // Ajoutez les autres éléments de la soumission
    $submission->addChild('title', $_POST['title']);
    $submission->addChild('abstract', $_POST['abstract']);
    $submission->addChild('keywords', $_POST['keywords']);
    $submission->addChild('disciplines', $_POST['disciplines']);
    $submission->addChild('speciality', $_POST['speciality']);
    $submission->addChild('theme', $_POST['theme']);
    $submission->addChild('institution', $_POST['institution']);
    // Structure avec attribut
$structure = $submission->addChild('structure');
$structure->addAttribute('type', $_POST['structureType']);

// Emails (Institutionnel et Autre)
if (!empty($_POST['emailInstitutionnel'])) {
    $mail = $submission->addChild('mail', $_POST['emailInstitutionnel']);
    $mail->addAttribute('type', 'institutional');
}
if (!empty($_POST['emailAutre'])) {
    $mail = $submission->addChild('mail', $_POST['emailAutre']);
    $mail->addAttribute('type', 'autre');
}

// Téléphone
$submission->addChild('phone', $_POST['phone']);

// Adresse
$address = $submission->addChild('address');
$address->addChild('ville', $_POST['city']);

// Superviseur
$supervisor = $submission->addChild('supervisor');
$supervisor->addChild('firstname', $_POST['supervisorFirstname']);
$supervisor->addChild('lastname', $_POST['supervisorLastname']);
$supervisor->addChild('mail', $_POST['supervisorEmail']);
$supervisor->addChild('institution', $_POST['supervisorInstitution']);

// Co-superviseur (vérifiez si les champs sont remplis)
if (!empty($_POST['cosupervisorFirstname']) || !empty($_POST['cosupervisorLastname'])) {
    $cosupervisor = $submission->addChild('cosupervisor');
    $cosupervisor->addChild('firstname', $_POST['cosupervisorFirstname']);
    $cosupervisor->addChild('lastname', $_POST['cosupervisorLastname']);
    $cosupervisor->addChild('mail', $_POST['cosupervisorEmail']);
    $cosupervisor->addChild('institution', $_POST['cosupervisorInstitution']);
}

     // Convertissez l'objet SimpleXMLElement en chaîne de caractères XML
     $xmlString = $xml->asXML();

     // Chargez cette chaîne dans un objet DOMDocument pour le formater
     $dom = new DOMDocument();
     $dom->preserveWhiteSpace = false;
     $dom->formatOutput = true;
     $dom->loadXML($xmlString);
 
     // Sauvegardez le XML formaté dans un fichier
     $dom->save($filePath);
      // Mettre à jour la colonne premiere_connexion à false
      $apogee=   $_POST['firstauthorApogeCode'];
      $update_sql = "UPDATE Etudiants SET premiere_connexion = FALSE WHERE code_apogee='$apogee' ";
     $conn->query($update_sql);

     session_start();
     $_SESSION['CONNECT']="OK3";

    // Redirigez
    header("Location: enregistrement_succ.php");
    exit();
}
?>
