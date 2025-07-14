<?php
include '../includes/db.php';
include_once '../fonction/fonction.php';
$conn = connectlocal();

$categorie = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;
$categories = getCategories();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajout_objet'])) {
    $nom_objet = trim($_POST['nom_objet']);
    $id_categorie = intval($_POST['id_categorie']);
    $id_membre = 1;
    if (isset($_FILES['image_objet']) && $_FILES['image_objet']['error'] === UPLOAD_ERR_OK) {
        $message = ajouterObjet($nom_objet, $id_categorie, $id_membre, $_FILES['image_objet']);
    } else {
        $message = '<div class="alert alert-warning">Veuillez sélectionner une image.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.3.5-dist/bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../includes/style.css">
    <script src="../../bootstrap-5.3.5-dist/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <title>Liste des objets</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <div class="card shadow">
                    <a href="fichemembre.php" class="btn btn-outline-primary mb-3">📋 Voir la liste des membres</a>
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Ajouter un objet</h4>
                    </div>
                    <div class="card-body">
                        <?= $message ?>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="ajout_objet" value="1">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <input type="text" name="nom_objet" class="form-control" placeholder="Nom de l'objet" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="id_categorie" class="form-select" required>
                                        <option value="">Catégorie</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="file" name="image_objet" class="form-control" accept="image/*" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h2 class="mb-0">Liste des objets</h2>
                    </div>
                    <div class="card-body">
                        <form method="get" class="mb-4">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <label for="categorie" class="form-label mb-0">Catégorie :</label>
                                </div>
                                <div class="col-auto">
                                    <select name="categorie" id="categorie" class="form-select">
                                        <option value="0">Toutes</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id_categorie'] ?>" <?= ($categorie == $cat['id_categorie']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['nom_categorie']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Filtrer</button>
                                </div>
                            </div>
                        </form>
                        <?php listeobjtFiltre($categorie); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>