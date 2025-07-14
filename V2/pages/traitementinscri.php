<?php
$Email = $_POST['email'];
$Motdepasse = $_POST['password'];
$genre = $_POST['genre'];
$ville = $_POST['ville'];
$datenaissance = $_POST['datenaissance'];
$nom = $_POST['nom'];
$image = $_POST['photo'];

include '../includes/db.php';
$conn= connectlocal();


$sql = mysqli_query($bdd, "INSERT INTO membre WHERE email = '$Email' AND mdp = '$Motdepasse' AND genre = '$genre' AND ville = '$ville' AND datenaissance = '$datenaissance' AND nom = '$nom' AND image_profil = '$image'");

if (mysqli_fetch_assoc($sql)) {
    
    header('Location: listeobj.php');
    exit;
} else {
    
    header('Location: index.php?error=login_failed');
    exit;
}

mysqli_close($bdd);
?>