<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';

$conn = connectlocal();
if (!empty($_POST['rendre'])) {
    $id_emprunt = intval($_POST['id_emprunt']);
    $etat = $conn->real_escape_string($_POST['etat_retour']);
    $date_retour = date('Y-m-d');
    $sql = "UPDATE emprunt SET date_retour='$date_retour', etat_retour='$etat' WHERE id_emprunt=$id_emprunt";
    if ($conn->query($sql)) {
        $message = "<div class='alert alert-success'>Objet rendu avec succès.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Erreur lors de la mise à jour.</div>";
    }
}

// récupérer les membres
$sql = "SELECT * FROM membre ORDER BY nom";
$result = $conn->query($sql);
$membres = [];
while ($row = $result->fetch_assoc()) {
    $membres[] = $row;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Membres et emprunts</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <a href="listeobj.php" class="btn btn-outline-primary mb-3">Liste des objets</a>
<h1 class="text-center mb-4">Membres et leurs emprunts</h1>

<?= $message ?? '' ?>

<?php foreach ($membres as $membre): ?>
<div class="card mb-3 shadow">
    <div class="card-header bg-primary text-white">
        <strong><?= htmlspecialchars($membre['nom']) ?></strong> — <?= htmlspecialchars($membre['email']) ?>
    </div>
    <div class="card-body">
        <?php
        $id_membre = $membre['id_membre'];
        $sqlEmp = "
            SELECT e.*, o.nom_objet, c.nom_categorie
            FROM emprunt e
            JOIN objet o ON o.id_objet = e.id_objet
            JOIN categorie_objet c ON c.id_categorie = o.id_categorie
            WHERE e.id_membre=$id_membre
            ORDER BY e.date_emprunt DESC
        ";
        $resEmp = $conn->query($sqlEmp);
        $emprunts = [];
        while ($row = $resEmp->fetch_assoc()) {
            $emprunts[] = $row;
        }
        ?>

        <?php if (!$emprunts): ?>
            <p>Aucun emprunt.</p>
        <?php else: ?>
            <table class="table table-sm table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Objet</th>
                        <th>Catégorie</th>
                        <th>Date emprunt</th>
                        <th>Date retour</th>
                        <th>État</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($emprunts as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['nom_objet']) ?></td>
                        <td><?= htmlspecialchars($e['nom_categorie']) ?></td>
                        <td><?= htmlspecialchars($e['date_emprunt']) ?></td>
                        <td><?= $e['date_retour'] ?: '-' ?></td>
                        <td><?= $e['etat_retour'] ?: '-' ?></td>
                        <td>
                            <?php if (empty($e['date_retour'])): ?>
                            <form method="post" class="d-flex gap-2">
                                <input type="hidden" name="id_emprunt" value="<?= $e['id_emprunt'] ?>">
                                <select name="etat_retour" class="form-select form-select-sm" required>
                                    <option value="OK">OK</option>
                                    <option value="Abîmé">Abîmé</option>
                                </select>
                                <button name="rendre" class="btn btn-success btn-sm">Rendre</button>
                            </form>
                            <?php else: ?>
                            <span class="text-success">Rendu</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
</div>
</body>
</html>
