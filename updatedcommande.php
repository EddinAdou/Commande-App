<?php
include_once("main.php");
$client = true;

// Récupération des informations de la commande avec le nom du client
$query = "
    SELECT commande.*, client.nom 
    FROM commande 
    JOIN client ON commande.idclient = client.idclient 
    WHERE commande.idcommande = :id
";
$pdostmt = $pdo->prepare($query);
$pdostmt->execute(["id" => $_GET["id"] ?? null]);
$row = $pdostmt->fetch(PDO::FETCH_ASSOC);
$pdostmt->closeCursor();

// Vérification de l'existence du résultat
if (!$row) {
    echo "Erreur : commande introuvable ou le client associé n'existe pas.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $date = $_POST["inputdate"] ?? null;
    $idclient = $_POST["inputclient"] ?? null;
    $nom = $_POST["inputnom"] ?? null;

    // Vérification que les champs requis ne sont pas vides
    if ($date && $nom) {
        // Mise à jour de la commande et du nom du client
        $updateQuery = "
            UPDATE commande 
            JOIN client ON commande.idclient = client.idclient 
            SET commande.date = :date, client.nom = :nom 
            WHERE commande.idcommande = :id
        ";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            "date" => $date,
            "nom" => $nom,
            "id" => $_GET["id"] ?? null
        ]);

        // Redirection après la mise à jour
        header('Location: commandes.php');
        exit;
    } else {
        echo "Erreur : Veuillez remplir tous les champs requis.";
    }
}

if ($row) :
    ?>

    <?php include_once("header.php"); ?>
    <main class="flex-shrink-0">
        <div class="container">
            <h1 class="mt-5">Modifier une commande</h1>
            <form class="row g-3" method="POST">
                <div class="col-md-6">
                    <label for="inputclient" class="form-label">ID Client</label>
                    <input type="text" class="form-control" id="inputclient" name="inputclient" value="<?php echo htmlspecialchars($row['idclient'] ?? ''); ?>" >
                </div>
                <div class="col-md-6">
                    <label for="inputnom" class="form-label">Nom Client</label>
                    <input type="text" class="form-control" id="inputnom" name="inputnom" value="<?php echo htmlspecialchars($row['nom'] ?? ''); ?>" >
                </div>
                <div class="col-md-6">
                    <label for="inputdate" class="form-label">DATE</label>
                    <input type="date" class="form-control" id="inputdate" name="inputdate" value="<?php echo htmlspecialchars($row['date'] ?? ''); ?>" >
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </main>
    <?php include_once("footer.php"); ?>
<?php endif; ?>
