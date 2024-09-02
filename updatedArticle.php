<?php

include_once("main.php");

$query = "SELECT * FROM article WHERE idarticle = :id";
$pdostmt = $pdo->prepare($query);
$pdostmt->execute(["id" => $_GET["id"]]);
$row = $pdostmt->fetch(PDO::FETCH_ASSOC);
$pdostmt->closeCursor();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $description = $_POST["inputdesc"];
    $prix_unitaire = $_POST["inputpu"];

    // Mettre à jour les données dans la base de données
    $updateQuery = "UPDATE article SET description = :description, prix_unitaire = :prix_unitaire WHERE idarticle = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute([
        "description" => $description,
        "prix_unitaire" => $prix_unitaire,
        "id" => $_GET["id"]
    ]);

    // Redirection vers la page articles.php
    header('Location: articles.php');
    exit;
}

if ($row) :
?>

<?php include_once("header.php"); ?>
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5">Modifier un article</h1>
        <form class="row g-3" method="POST">
            <div class="col-md-6">
                <label for="inputdesc">Description</label>
                <textarea class="form-control" placeholder="Mettre la description ici" id="inputdesc" name="inputdesc" required><?php echo htmlspecialchars($row["description"]); ?></textarea>
            </div>
            <div class="col-md-6">
                <label for="inputpu" class="form-label">PU</label>
                <input type="text" class="form-control" id="inputpu" name="inputpu" placeholder="Prix unitaire" value="<?php echo htmlspecialchars($row["prix_unitaire"]); ?>" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Modifier</button>
            </div>
        </form>
    </div>
</main>
<?php include_once("footer.php"); ?>
<?php endif; ?>