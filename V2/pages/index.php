<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.3.5-dist/bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <script src="../../bootstrap-5.3.5-dist/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <title>Login</title>
</head>
<body class="bg-light">
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="row w-100 justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Connexion</h2>
                        <form action="traitementlogin.php" method="post">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="Alice Dupont" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" value="password1" required>
                            </div>
                            <?php 
                            if(isset($_GET['error']) && ($_GET['error'])==1){ ?>
                                <div class="alert alert-danger py-2" role="alert">
                                    Erreur de connexion, veuillez réessayer.
                                </div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary w-100">Valider</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="inscription.php" class="btn btn-link">Inscription</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>