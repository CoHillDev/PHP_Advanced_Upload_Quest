<?php

$uploadDir = 'public/uploads/';
// Les extensions autorisées :
$authorizedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
// Le poids max géré par PHP par défaut est de 1M
$maxFileSize = 1000000;
// On sécurise et on effectue les tests
$errors = [];

// On vérifie que le formulaire est correctement soumis
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    //$uploadDir = 'public/uploads/';
    // le nom de fichier sur le serveur est ici généré à partir du nom de fichier sur le poste du client (d'autres stratégies de nommage sont possibles)
    $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);
    // On récupère l'extension du fichier
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

    // Si l'extension n'est pas autorisée
    if ((!in_array($extension, $authorizedExtensions))) {
        $errors[] = 'Please select image type Jpg, Png, Gif or webp !';
    }

    //On vérifie si l'image existe et si le poids est autorisé en octets
    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize) {
        $errors[] = "Your image must be under 1Mo !";
    }

    //Si pas d'erreur, alors on upload l'image
    if (empty($errors)) {
        // On génère un nom de fichier unique pour éviter les écrasements de fichiers
        $uniqueFilename = uniqid() . '.' . $extension;
        $uploadFile = $uploadDir . $uniqueFilename;
        //Script d'upload : Si pas d"erreur, on déplace le fichier temporaire vers le nouvel emplacement sur le serveur
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);

        // Récupération des informations du formulaire 
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $age = trim($_POST['age']);


        echo '<fieldset link rel="stylesheet" href="style.css">
        <legend><h2>Driver Licence</h2></legend>';
        // On affiche l'image
        echo '<img src="' . $uploadFile . '" alt="Profile Picture" style="max-width: 150px;" link rel="stylesheet" href="style.css">';

        // On affiche les informations de l'utilisateur
        echo '<p>Firstname : ' . $firstname . '</p>';
        echo '<p>Lastname : ' . $lastname . '</p>';
        echo '<p>Age : ' . $age . ' years old</p>';
        echo '</div></fieldset>';
    } else {
        $errors[] = "Your upload went wrong, please try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Springfield Driving License Form</title>
</head>

<body>

    <section>
        <div class="container">
            <h1 class="text-center">Springfield Official Licence Services</h1>
            <form method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>To get your licence, fill this form Homer :</legend>
                    <div>
                        <label for="imageUpload">Upload a profile image</label>
                        <input type="file" name="avatar" id="imageUpload" class="form-control" required>
                    </div>
                    <div>
                        <label for="firstname">Firstname:</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" required>
                    </div>
                    <div>
                        <label for="lastname">Lastname:</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                    </div>
                    <div>
                        <label for="age">Age:</label>
                        <input type="number" name="age" id="age" class="form-control" required>
                    </div>
                    <button type="submit" style="background-color:red; border-color:red; color:white">Push
                        here!</button>
                </fieldset>

            </form>
            <?php
            if (!empty($errors)) {
                echo '<div style="color: red;">';
                foreach ($errors as $error) {
                    echo '<p>' . $error . '</p>';
                }
                echo '</div>';
            }
            ?>

        </div>
    </section>
</body>

</html>