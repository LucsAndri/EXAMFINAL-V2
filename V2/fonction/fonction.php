<?php
function afficherNomsImages() {
    $sql = "SELECT nom_image FROM images_objet ORDER BY nom_image ASC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo '<ul class="list-group">';
        while ($row = $result->fetch_assoc()) {
            echo '<li class="list-group-item">' . htmlspecialchars($row['nom_image']) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<div class="alert alert-warning">Aucune image trouvée dans la base.</div>';
    }
}

function getCategories() {
    global $conn;
    $catResult = $conn->query("SELECT id_categorie, nom_categorie FROM categorie_objet ORDER BY nom_categorie ASC");
    $categories = [];
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row;
    }
    return $categories;
}

function listeobjtFiltre($categorie = 0) {
    global $conn;
    $sql = "SELECT o.id_objet, o.nom_objet, i.nom_image 
            FROM objet o 
            LEFT JOIN images_objet i ON o.id_objet = i.id_objet";
    if ($categorie > 0) {
        $sql .= " WHERE o.id_categorie = " . intval($categorie);
    }
    $sql .= " ORDER BY o.nom_objet ASC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo '<table class="table table-striped align-middle">';
        echo '<thead class="table-primary"><tr>
                <th>Nom de l\'objet</th>
                <th>Image</th>
                <th>Emprunt</th>
              </tr></thead><tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['nom_objet']) . '</td>';
            $nomImage = trim($row['nom_image']);
            $imagePath = '../images/' . $nomImage;
            $imageDiskPath = __DIR__ . '/../images/' . $nomImage;
            if (!empty($nomImage) && file_exists($imageDiskPath)) {
                echo '<td><img class="img-thumbnail" style="max-width:100px;" src="' . $imagePath . '" alt="Image"></td>';
            } else {
                echo '<td><span class="text-muted">Aucune image ou image introuvable</span></td>';
            }
            $empruntSql = "SELECT date_retour FROM emprunt WHERE id_objet = " . intval($row['id_objet']) . " AND date_retour >= CURDATE() ORDER BY date_retour ASC LIMIT 1";
            $empruntResult = $conn->query($empruntSql);
            if ($empruntResult && $empruntResult->num_rows > 0) {
                $emprunt = $empruntResult->fetch_assoc();
                echo '<td><span class="text-danger">En cours d\'emprunt<br>Date de retour : ' . htmlspecialchars($emprunt['date_retour']) . '</span></td>';
            } else {
                echo '<td><span class="text-success">Disponible</span></td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-warning">Aucun objet trouvé.</div>';
    }
}
function ajouterObjet($nom_objet, $id_categorie, $id_membre, $image_file) {
    global $conn;
    $message = '';
    $img_name = basename($image_file['name']);
    $img_path = '../../images/' . $img_name;
    if (move_uploaded_file($image_file['tmp_name'], $img_path)) {
        $stmt = $conn->prepare("INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $nom_objet, $id_categorie, $id_membre);
        if ($stmt->execute()) {
            $id_objet = $conn->insert_id;
            $stmt2 = $conn->prepare("INSERT INTO images_objet (id_objet, nom_image) VALUES (?, ?)");
            $stmt2->bind_param("is", $id_objet, $img_name);
            $stmt2->execute();
            $message = '<div class="alert alert-success">Objet ajouté avec succès !</div>';
        } else {
            $message = '<div class="alert alert-danger">Erreur lors de l\'ajout de l\'objet.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Erreur lors de l\'upload de l\'image.</div>';
    }
    return $message;
}
?>
