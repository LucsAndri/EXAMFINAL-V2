
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.3.5-dist/bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <script src="../../bootstrap-5.3.5-dist/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <title>Inscription</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="mb-4 text-center">Inscription</h1>
                <form action="traitementinscri.php" class="border rounded p-4 shadow bg-light">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="datenaissance" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="datenaissance" name="datenaissance" required>
                    </div>
                    <div class="mb-3">
                        <label for="ville" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="ville" name="ville" required>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <select class="form-select" id="genre" name="genre" required>
                            <option value="">Sélectionner...</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo (URL)</label>
                        <input type="text" class="form-control" id="photo" name="photo">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Valider</button>
                </form>
                <div class="text-center mt-3">
                    <a href="index.php" class="btn btn-link">Déjà inscrit ? Se connecter</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>