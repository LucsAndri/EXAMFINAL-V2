<?php
include '../includes/db.php';
include_once '../fonction/fonction.php';
$conn = connectlocal();

$id_objet = isset($_GET['id']) ? intval($_GET['id']) : 0;
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