<?php

include '../includes/db.php';
include_once '../fonction/fonction.php';
$conn = connectserv();

$id_objet = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_image'])) {
    $id_image = intval($_POST['supprimer_image']);
    $message = supprimerImageObjet($id_image);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche objet</title>
    <link rel="stylesheet" href="../../bootstrap-5.3.5-dist/bootstrap-5.3.5-dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?= $message ?>
        <?php
        if ($id_objet > 0) {
            afficherFicheObjet($id_objet);
        } else {
            echo '<div class="alert alert-danger">Objet non trouvé.</div>';
        }
        ?>
        <a href="listeobj.php" class="btn btn-secondary mt-3">Retour à la liste</a>
    </div>
</body>
</html>