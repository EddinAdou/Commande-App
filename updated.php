<?php
$client = true;
include_once("main.php");
$pdo = (new Connexion())->getPDO();
if (!empty($_GET["id"])) {
    $query = "SELECT * FROM client WHERE idclient = :id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["id" => $_GET["id"]]);
    $row = $pdostmt->fetch(PDO::FETCH_ASSOC);
    $pdostmt->closeCursor();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $nom = $_POST["inputnom"];
        $ville = $_POST["inputville"];
        $telephone = $_POST["inputtel"];

        // Mettre à jour les données dans la base de données
        $updateQuery = "UPDATE client SET nom = :nom, ville = :ville, telephone = :telephone WHERE idclient = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            "nom" => $nom,
            "ville" => $ville,
            "telephone" => $telephone,
            "id" => $_GET["id"]
        ]);

        // Redirection vers la page clients.php
        header('Location: clients.php');
        exit;
    }

    if ($row) :
        include_once("header.php");
        ?>
        <main class="flex-shrink-0">
            <div class="container">
                <h1 class="mt-5">Modifier un client</h1>

                <form class="row g-3" method="POST">
                    <div class="col-md-6">
                        <label for="inputnom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="inputnom" name="inputnom" value="<?php echo htmlspecialchars($row['nom']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputville" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="inputville" name="inputville" value="<?php echo htmlspecialchars($row['ville']); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="inputtel" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="inputtel" name="inputtel" value="<?php echo htmlspecialchars($row['telephone']); ?>" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </main>
    <?php
    endif;
}
?>