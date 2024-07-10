<?php
session_start();

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['CONNECT']) || $_SESSION['CONNECT'] !== 'OK2') {
 
      
    header('Location: login.html?error=1');         
    exit();
}

$apogee=$_SESSION['apogee'];

$a=0;

$filePath = 'submissions.xml';

if (file_exists($filePath)) {
    $xml = simplexml_load_file($filePath);
    $submissionToEdit = null;
    foreach ($xml->submission as $submission) {
        if ($submission->firstauthor['ApogeCode'] == $apogee) {
            $submissionToEdit = $submission;
            break;
        }
    }

    $emailInstitutionnel = '';
    $emailAutre = '';
    
    if (isset($submissionToEdit->mail)) {
        foreach ($submissionToEdit->mail as $mail) {
            if ($mail['type'] == 'institutionnel') {
                $emailInstitutionnel = (string)$mail;
            } else if ($mail['type'] == 'autre') {
                $emailAutre = (string)$mail;
            }
        }
    }
}



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $apogee = $_POST['firstauthorApogeCode']; // Utilisez le champ ApogeCode comme identifiant unique
        $filePath = 'submissions.xml';
    
        if (file_exists($filePath)) {
            $xml = simplexml_load_file($filePath);
            foreach ($xml->submission as $submission) {
                if ($submission->firstauthor['ApogeCode'] == $apogee) {
                    // Mise à jour des informations de l'auteur principal
                    $submission->firstauthor['sex'] = $_POST['firstauthorSex'];
                    $submission->firstauthor['registrationYear'] = $_POST['firstauthorRegistrationYear'];
                    $submission->firstauthor->firstname = $_POST['firstauthorFirstname'];
                    $submission->firstauthor->lastname = $_POST['firstauthorLastname'];
                    $submission->firstauthor->birth = $_POST['firstauthorBirth'];
                    $authorsData = [];
                    // Mise à jour des coauteurs (s'il y en a)
                    if (isset($_POST['coauthorFirstname'])) {
                        $countCoauthors = count($_POST['coauthorFirstname']);
        
                        for ($i = 0; $i < $countCoauthors; $i++) {
                            $authorsData[] = [
                                'sex' => $_POST['coauthorSex'][$i],
                                'ApogeCode' => $_POST['coauthorApogeCode'][$i],
                                'registrationYear' => $_POST['coauthorRegistrationYear'][$i],
                                'firstname' => $_POST['coauthorFirstname'][$i],
                                'lastname' => $_POST['coauthorLastname'][$i],
                                'birth' => $_POST['coauthorBirth'][$i],
                            ];
                        }
                    }
                
                    // Mettre à jour ou créer des coauteurs basés sur les données soumises
                    foreach ($authorsData as $index => $authorsData) {
                        if (isset($submission->author[$index])) {
                            // Mise à jour du coauteur existant
                            $author = $submission->author[$index];
                        } else {
                            // Ajout d'un nouveau coauteur
                            $author = $submission->addChild('author');
                        }
                        
                        $author['sex'] = $authorsData['sex'];
                        $author['ApogeCode'] = $authorsData['ApogeCode'];
                        $author['registrationYear'] = $authorsData['registrationYear'];
                        $author->firstname = $authorsData['firstname'];
                        $author->lastname = $authorsData['lastname'];
                        $author->birth = $authorsData['birth'];
                    }
                    
   
            
                    }
    
                    // Mise à jour des autres informations
                    $submission->title = $_POST['title'];
                    $submission->abstract = $_POST['abstract'];
                    $submission->keywords = $_POST['keywords'];
                    $submission->disciplines = $_POST['disciplines'];
                    $submission->speciality = $_POST['speciality'];
                    $submission->theme = $_POST['theme'];
                    $submission->institution = $_POST['institution'];
                    $submission->structure['type'] = $_POST['structureType'];
                     // Gestion des e-mails
                foreach ($submission->mail as $mail) {
                    if ((string)$mail['type'] == 'institutionnel') {
                        $mail[0] = $_POST['emailInstitutionnel'];
                    } elseif ((string)$mail['type'] == 'autre') {
                        $mail[0] = $_POST['emailAutre'];
                    }
                }

                // Gestion des informations de contact
                $submission->phone = $_POST['phone'];
                $submission->address->ville = $_POST['address'];

                // Gestion du superviseur
                $submission->supervisor->firstname = $_POST['supervisorFirstname'];
                $submission->supervisor->lastname = $_POST['supervisorLastname'];
                $submission->supervisor->mail = $_POST['supervisorEmail'];
                $submission->supervisor->institution = $_POST['supervisorInstitution'];

                // Gestion du co-superviseur
                if(isset($_POST['cosupervisorFirstname'])) { // Vérifiez si le co-superviseur a été soumis
                    if(!isset($submission->cosupervisor)) { // Si non existant, créez l'élément
                        $submission->addChild('cosupervisor');
                    }
                    $submission->cosupervisor->firstname = $_POST['cosupervisorFirstname'];
                    $submission->cosupervisor->lastname = $_POST['cosupervisorLastname'];
                    $submission->cosupervisor->mail = $_POST['cosupervisorEmail'];
                    $submission->cosupervisor->institution = $_POST['cosupervisorInstitution'];
                }
    
                    // Sauvegardez les modifications
                    $dom = new DOMDocument('1.0');
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $dom->loadXML($xml->asXML());
                    $dom->save($filePath);

                    header("Location: modifier_succ.html");

    
                    break; // Sortie de la boucle une fois la modification effectuée
                }
            }
        }
    

       


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modification des Informations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
<nav id="navbar2">
    <div class="navbar-content">
        Formulaire de Modification des Informations
    </div>
</nav>

<form id="studentForm" action="page_modification.php" method="POST">
    <!-- Auteur principal -->
    <fieldset>
        <legend>Auteur principal</legend>
        Sexe:
            <select name="firstauthorSex">
                <option value="M" <?php if($submissionToEdit->firstauthor['sex'] == 'M') echo 'selected'; ?>>M</option>
                <option value="F" <?php if($submissionToEdit->firstauthor['sex'] == 'F') echo 'selected'; ?>>F</option>
            </select>
         Code Apogée: <input type="text" name="firstauthorApogeCode" value="<?php echo $submissionToEdit->firstauthor['ApogeCode']; ?>" required readonly>
         Année d'inscription: <select name="firstauthorRegistrationYear">
                <!-- Dynamiquement sélectionner l'année d'inscription -->
                <?php for($i = 1; $i <= 6; $i++): ?>
                <option value="<?php echo $i; ?>" <?php if($submissionToEdit->firstauthor['registrationYear'] == $i) echo 'selected'; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
         Prénom: <input type="text" name="firstauthorFirstname" value="<?php echo $submissionToEdit->firstauthor->firstname; ?>" required>
        Nom: <input type="text" name="firstauthorLastname" value="<?php echo $submissionToEdit->firstauthor->lastname; ?>" required>
        Date de naissance: <input type="date" name="firstauthorBirth" value="<?php echo $submissionToEdit->firstauthor->birth; ?>" required>
    </fieldset>
    
        <!-- Section pour les coauteurs existants -->

 
    <?php if (isset($submissionToEdit->author)): ?>
        <?php foreach ($submissionToEdit->author as $index => $author): ?>
            <div class="author-info">
            <fieldset>
            <legend>Coauteur <?php echo $a+1 ; ?></legend>
                Sexe: <input type="text" name="coauthorSex[]" value="<?php echo $author['sex']; ?>">
                Code Apogée: <input type="text" name="coauthorApogeCode[]" value="<?php echo $author['ApogeCode']; ?>" readonly>
                Année d'inscription: <select name="coauthorRegistrationYear[]">
                <!-- Dynamiquement sélectionner l'année d'inscription -->
                <?php for($i = 1; $i <= 6; $i++): ?>
                <option value="<?php echo $i; ?>" <?php if($submissionToEdit->author['registrationYear'] == $i) echo 'selected'; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
                Prénom: <input type="text" name="coauthorFirstname[]" value="<?php echo $author->firstname; ?>">
                Nom: <input type="text" name="coauthorLastname[]" value="<?php echo $author->lastname; ?>">
                Date de naissance: <input type="date" name="coauthorBirth[]" value="<?php echo $author->birth; ?>">
                </fieldset>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    Titre: <input type="text" name="title" value="<?php echo $submissionToEdit->title; ?>" required>
    Résumé: <textarea name="abstract" maxlength="1000" required><?php echo $submissionToEdit->abstract; ?></textarea>
    Mots-clés: <input type="text" name="keywords" value="<?php echo $submissionToEdit->keywords; ?>" required>
    <select name="disciplines" required>
            <option value="Math" <?php if ($submissionToEdit->disciplines == 'Math') echo 'selected'; ?>>Math</option>
            <option value="Informatique" <?php if ($submissionToEdit->disciplines == 'Informatique') echo 'selected'; ?>>Informatique</option>
            <option value="Biologie" <?php if ($submissionToEdit->disciplines == 'Biologie') echo 'selected'; ?>>Biologie</option>
            <option value="Environnement" <?php if ($submissionToEdit->disciplines == 'Environnement') echo 'selected'; ?>>Environnement</option>
            <option value="chimie" <?php if ($submissionToEdit->disciplines == 'chimie') echo 'selected'; ?>>chimie</option>
            <option value="Physics" <?php if ($submissionToEdit->disciplines == 'Physics') echo 'selected'; ?>>Physics</option>

    
        </select>
    Spécialité: <input type="text" name="speciality" value="<?php echo $submissionToEdit->speciality; ?>" required>
    Thème: 
    <select name="theme" required>
    <option value="Algebre lineaire et ses applications" <?php if ($submissionToEdit->theme == 'Algebre lineaire et ses applications') echo 'selected'; ?>>Algebre lineaire et ses applications</option>
    <option value="Calcul differentiel et integral" <?php if ($submissionToEdit->theme == 'Calcul differentiel et integral') echo 'selected'; ?>>Calcul differentiel et integral
</option>
    <option value="Theorie des nombres et cryptographie" <?php if ($submissionToEdit->theme == 'Theorie des nombres et cryptographie') echo 'selected'; ?>>Theorie des nombres et cryptographie</option>

    <option value="Intelligence artificielle et apprentissage automatique" <?php if ($submissionToEdit->theme == 'Intelligence artificielle et apprentissage automatique') echo 'selected'; ?>>Intelligence artificielle et apprentissage automatique</option>
    <option value="Reseaux informatiques et securite" <?php if ($submissionToEdit->theme == 'Reseaux informatiques et securite') echo 'selected'; ?>>Reseaux informatiques et securite</option>
    <option value="Developpement web et mobile" <?php if ($submissionToEdit->theme == 'Developpement web et mobile') echo 'selected'; ?>>Developpement web et mobile</option>
    <option value="Mecanique classique et mouvement des corps" <?php if ($submissionToEdit->theme == 'Mecanique classique et mouvement des corps') echo 'selected'; ?>>Mecanique classique et mouvement des corps</option>
    <option value="Physique quantique et ses applications" <?php if ($submissionToEdit->theme == 'Physique quantique et ses applications') echo 'selected'; ?>>Physique quantique et ses applications</option>
    <option value="Astrophysique et cosmologie" <?php if ($submissionToEdit->theme == 'Astrophysique et cosmologie') echo 'selected'; ?>>Astrophysique et cosmologie</option>
    <option value="Genetique et biotechnologie" <?php if ($submissionToEdit->theme == 'Genetique et biotechnologie') echo 'selected'; ?>>Genetique et biotechnologie</option>
    <option value="Ecologie et dynamique des populations" <?php if ($submissionToEdit->theme == 'Ecologie et dynamique des populations') echo 'selected'; ?>>Ecologie et dynamique des populations</option>
    <option value="Biologie cellulaire et moleculaire" <?php if ($submissionToEdit->theme == 'Biologie cellulaire et moleculaire') echo 'selected'; ?>>Biologie cellulaire et moleculaire</option>
    <option value="Changement climatique et ses impacts" <?php if ($submissionToEdit->theme == 'Changement climatique et ses impacts') echo 'selected'; ?>>Changement climatique et ses impacts</option>
    <option value="Gestion des ressources naturelles et durabilite" <?php if ($submissionToEdit->theme == 'Gestion des ressources naturelles et durabilite') echo 'selected'; ?>>Gestion des ressources naturelles et durabilite</option>
    <option value="Ecologie urbaine et gestion des dechets" <?php if ($submissionToEdit->theme == 'Ecologie urbaine et gestion des dechets') echo 'selected'; ?>>Ecologie urbaine et gestion des dechets</option>
    <option value="Chimie organique synthetique" <?php if ($submissionToEdit->theme == 'Chimie organique synthetique') echo 'selected'; ?>>Chimie organique synthetique</option>
    <option value="Chimie inorganique avancee " <?php if ($submissionToEdit->theme == 'Chimie inorganique avancee ') echo 'selected'; ?>>Chimie inorganique avancee </option>
    <option value="Chimie analytique et instrumentation" <?php if ($submissionToEdit->theme == 'Chimie analytique et instrumentation') echo 'selected'; ?>>Chimie analytique et instrumentation</option>
   
</select>



    Institution: 
    <select name="institution" required>
            <option value="A" <?php if ($submissionToEdit->institution == 'A') echo 'selected'; ?>>Institution A</option>
            <option value="B" <?php if ($submissionToEdit->institution == 'B') echo 'selected'; ?>>Institution B</option>
            <option value="C" <?php if ($submissionToEdit->institution == 'C') echo 'selected'; ?>>Institution C</option>
            <option value="D" <?php if ($submissionToEdit->institution == 'D') echo 'selected'; ?>>Institution D</option>
            <option value="E" <?php if ($submissionToEdit->institution == 'E') echo 'selected'; ?>>Institution E</option>
        </select>
    Structure: 
    <select name="structureType" required>
            <option value="Laboratoire" <?php if ($submissionToEdit->structure['type'] == 'Laboratoire') echo 'selected'; ?>>Laboratoire</option>
            <option value="Groupe" <?php if ($submissionToEdit->structure['type'] == 'Groupe') echo 'selected'; ?>>Groupe</option>
        </select>
    
    Email (institutionnel): <input type="email" name="emailInstitutionnel" value="<?php echo htmlspecialchars($emailInstitutionnel); ?>">
    Email (autre): <input type="email" name="emailAutre" value="<?php echo htmlspecialchars($emailInstitutionnel); ?>">
    
    Téléphone: <input type="text" name="phone" value="<?php echo $submissionToEdit->phone; ?>">
    Adresse: <input type="text" name="address" value="<?php echo $submissionToEdit->address->ville; ?>">
    
    <!-- Superviseur -->
    <fieldset>
        <legend>Superviseur</legend>
        Prénom: <input type="text" name="supervisorFirstname" value="<?php echo $submissionToEdit->supervisor->firstname; ?>" required>
        Nom: <input type="text" name="supervisorLastname" value="<?php echo $submissionToEdit->supervisor->lastname; ?>" required>
        Email: <input type="email" name="supervisorEmail" value="<?php echo $submissionToEdit->supervisor->mail; ?>" required>
        Institution: <input type="text" name="supervisorInstitution" value="<?php echo $submissionToEdit->supervisor->institution; ?>" required>
    </fieldset>
    
    <!-- Co-superviseur -->
    <fieldset>
        <legend>Co-superviseur</legend>
        Prénom: <input type="text" name="cosupervisorFirstname" value="<?php echo isset($submissionToEdit->cosupervisor->firstname) ? $submissionToEdit->cosupervisor->firstname : ''; ?>">
Nom: <input type="text" name="cosupervisorLastname" value="<?php echo isset($submissionToEdit->cosupervisor->lastname) ? $submissionToEdit->cosupervisor->lastname : ''; ?>">
Email: <input type="email" name="cosupervisorEmail" value="<?php echo isset($submissionToEdit->cosupervisor->mail) ? $submissionToEdit->cosupervisor->mail : ''; ?>">
Institution: <input type="text" name="cosupervisorInstitution" value="<?php echo isset($submissionToEdit->cosupervisor->institution) ? $submissionToEdit->cosupervisor->institution : ''; ?>">
</fieldset>
<button type="submit"  style="background:green;">Modifier</button>
<button type="button" id="quitBtn">Quitter</button>
</form>
 <script>
    var quitBtn = document.getElementById("quitBtn");

// Ajoute un écouteur d'événements pour le clic sur le bouton "Quitter"
quitBtn.addEventListener("click", function() {
    // Redirige l'utilisateur vers une autre page (remplacez "nom_de_la_page.html" par le nom de votre page de redirection)
    window.location.href = "exit.php";
});
    </script>
</body>
</html>