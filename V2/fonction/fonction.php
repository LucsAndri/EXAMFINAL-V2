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
function listeobjtFiltre($categorie = 0, $nom_objet = '', $disponible = false) {
    global $conn;
    $sql = "SELECT o.id_objet, o.nom_objet, i.nom_image 
            FROM objet o 
            LEFT JOIN images_objet i ON o.id_objet = i.id_objet WHERE 1";
    if ($categorie > 0) {
        $sql .= " AND o.id_categorie = " . intval($categorie);
    }
    if ($nom_objet !== '') {
        $sql .= " AND o.nom_objet LIKE '%" . $conn->real_escape_string($nom_objet) . "%'";
    }
    if ($disponible) {
        $sql .= " AND o.id_objet NOT IN (SELECT id_objet FROM emprunt WHERE date_retour >= CURDATE())";
    }
    $sql .= " ORDER BY o.nom_objet ASC";
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
                <th>Fiche</th>
              </tr></thead><tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            // Nom de l'objet avec lien vers la fiche
            echo '<td><a href="objet.php?id=' . $row['id_objet'] . '" class="text-decoration-none text-primary">' . htmlspecialchars($row['nom_objet']) . '</a></td>';
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
            // Bouton fiche
            echo '<td><a href="objet.php?id=' . $row['id_objet'] . '" class="btn btn-outline-primary btn-sm">Voir fiche</a></td>';
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

    $img_name = strtolower(str_replace(' ', '_', iconv('UTF-8', 'ASCII//TRANSLIT', $nom_objet))) . '.jpg';
    $img_path = '../images/' . $img_name;

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
function afficherFicheObjet($id_objet) {
    global $conn;
    $sql = "SELECT o.nom_objet, o.id_categorie, o.id_membre, c.nom_categorie 
            FROM objet o 
            JOIN categorie_objet c ON o.id_categorie = c.id_categorie 
            WHERE o.id_objet = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_objet);
    $stmt->execute();
    $objet = $stmt->get_result()->fetch_assoc();

    $imgSql = "SELECT nom_image FROM images_objet WHERE id_objet = ?";
    $imgStmt = $conn->prepare($imgSql);
    $imgStmt->bind_param("i", $id_objet);
    $imgStmt->execute();
    $imgResult = $imgStmt->get_result();
    $images = [];
    while ($img = $imgResult->fetch_assoc()) {
        $images[] = $img['nom_image'];
    }

    $empSql = "SELECT e.date_emprunt, e.date_retour, m.nom 
               FROM emprunt e 
               JOIN membre m ON e.id_membre = m.id_membre 
               WHERE e.id_objet = ? ORDER BY e.date_emprunt DESC";
    $empStmt = $conn->prepare($empSql);
    $empStmt->bind_param("i", $id_objet);
    $empStmt->execute();
    $empResult = $empStmt->get_result();

    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-primary text-white"><h3>' . htmlspecialchars($objet['nom_objet']) . '</h3></div>';
    echo '<div class="card-body">';
    echo '<p><strong>Catégorie :</strong> ' . htmlspecialchars($objet['nom_categorie']) . '</p>';
    echo '<div class="mb-3">';
    if (count($images) > 0) {
        echo '<img src="../images/' . htmlspecialchars($images[0]) . '" class="img-fluid mb-2" style="max-width:300px;" alt="Image principale">';
        if (count($images) > 1) {
            echo '<div class="d-flex flex-wrap">';
            foreach (array_slice($images, 1) as $img) {
                echo '<img src="../images/' . htmlspecialchars($img) . '" class="img-thumbnail m-1" style="max-width:100px;" alt="Autre image">';
            }
            echo '</div>';
        }
    } else {
        echo '<img src="../images/default.jpg" class="img-fluid mb-2" style="max-width:300px;" alt="Image par défaut">';
    }
    echo '</div>';
    echo '<h5>Historique des emprunts</h5>';
    if ($empResult->num_rows > 0) {
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>Membre</th><th>Date emprunt</th><th>Date retour</th></tr></thead><tbody>';
        while ($emp = $empResult->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($emp['nom']) . '</td>';
            echo '<td>' . htmlspecialchars($emp['date_emprunt']) . '</td>';
            echo '<td>' . htmlspecialchars($emp['date_retour']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>Aucun emprunt pour cet objet.</p>';
    }
    echo '</div></div>';
}

function afficherFicheMembre($id_membre) {
    global $conn;
    // Infos du membre
    $sql = "SELECT * FROM membre WHERE id_membre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_membre);
    $stmt->execute();
    $membre = $stmt->get_result()->fetch_assoc();

    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-primary text-white"><h3>' . htmlspecialchars($membre['nom']) . '</h3></div>';
    echo '<div class="card-body">';
    echo '<p><strong>Email :</strong> ' . htmlspecialchars($membre['email']) . '</p>';
    echo '<p><strong>Ville :</strong> ' . htmlspecialchars($membre['ville']) . '</p>';
    echo '<p><strong>Date de naissance :</strong> ' . htmlspecialchars($membre['date_naissance']) . '</p>';
    echo '<img src="../images/' . htmlspecialchars($membre['image_profil']) . '" class="img-thumbnail mb-3" style="max-width:120px;">';

    // Objets regroupés par catégorie
    $catSql = "SELECT c.nom_categorie, o.nom_objet 
               FROM objet o 
               JOIN categorie_objet c ON o.id_categorie = c.id_categorie 
               WHERE o.id_membre = ? 
               ORDER BY c.nom_categorie, o.nom_objet";
    $catStmt = $conn->prepare($catSql);
    $catStmt->bind_param("i", $id_membre);
    $catStmt->execute();
    $catResult = $catStmt->get_result();

    $grouped = [];
    while ($row = $catResult->fetch_assoc()) {
        $grouped[$row['nom_categorie']][] = $row['nom_objet'];
    }

    echo '<h5>Objets du membre</h5>';
    foreach ($grouped as $cat => $objs) {
        echo '<strong>' . htmlspecialchars($cat) . ' :</strong> ';
        echo implode(', ', array_map('htmlspecialchars', $objs));
        echo '<br>';
    }
    echo '</div></div>';
}
function supprimerImageObjet($id_image) {
    global $conn;
    // Récupérer le nom de l'image
    $sql = "SELECT nom_image FROM images_objet WHERE id_image = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_image);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($img = $result->fetch_assoc()) {
        $img_path = __DIR__ . '/../images/' . $img['nom_image'];
        // Supprimer le fichier si il existe
        if (file_exists($img_path)) {
            unlink($img_path);
        }
        // Supprimer de la table
        $delSql = "DELETE FROM images_objet WHERE id_image = ?";
        $delStmt = $conn->prepare($delSql);
        $delStmt->bind_param("i", $id_image);
        $delStmt->execute();
        return '<div class="alert alert-success">Image supprimée.</div>';
    } else {
        return '<div class="alert alert-danger">Image introuvable.</div>';
    }
}

?>
