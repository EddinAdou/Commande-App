<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$index = true;

include_once("main.php");

if (!isset($pdo)) {
    die("Erreur : La connexion à la base de données n'est pas établie.");
}

if (!empty($_GET["id"])) {
    $query = "
    SELECT c.nom, c.telephone, c.ville, cm.date, a.description, lc.quantite, a.prix_unitaire, cm.view
    FROM client c
    JOIN commande cm ON c.idclient = cm.idclient
    JOIN ligne_commande lc ON cm.idcommande = lc.idcommande
    JOIN article a ON lc.idarticle = a.idarticle
    WHERE cm.idcommande = :id
";
    $objstmt = $pdo->prepare($query);
    $objstmt->execute(["id" => $_GET["id"]]);
    $row = $objstmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("Erreur : Aucune commande trouvée avec cet ID.");
    }
} else {
    die("Erreur : Aucun ID de commande fourni.");
}

$query_view = "UPDATE commande SET view = view + 1 WHERE idcommande = :id";
$objstmt_view = $pdo->prepare($query_view);
$objstmt_view->execute(["id" => $_GET["id"]]);

$query_view_select = "SELECT view FROM commande WHERE idcommande = :id";
$objstmt_view_select = $pdo->prepare($query_view_select);
$objstmt_view_select->execute(["id" => $_GET["id"]]);
$row_view = $objstmt_view_select->fetch(PDO::FETCH_ASSOC);

include_once("header.php");
?>
<main class="flex-shrink-0">
    <div class="container">
        <div style="float: right; color:blue;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye"
                viewBox="0 0 16 16">
                <path
                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
            </svg>
            <?php echo $row_view['view']; ?>
        </div>

        <h1 class="mt-5">Détail de la commande</h1>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="inputnom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="inputnom" name="inputnom"
                    value="<?php echo htmlspecialchars($row['nom'] ?? '', ENT_QUOTES); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="inputtelephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="inputtelephone" name="inputtelephone"
                    value="<?php echo htmlspecialchars($row['telephone'] ?? '', ENT_QUOTES); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="inputville" class="form-label">Ville</label>
                <input type="text" class="form-control" id="inputville" name="inputville"
                    value="<?php echo htmlspecialchars($row['ville'] ?? '', ENT_QUOTES); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="inputdate" class="form-label">Date</label>
                <input type="text" class="form-control" id="inputdate" name="inputdate"
                    value="<?php echo htmlspecialchars($row['date'] ?? '', ENT_QUOTES); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="inputdescription" class="form-label">Description</label>
                <input type="text" class="form-control" id="inputdescription" name="inputdescription"
                    value="<?php echo htmlspecialchars($row['description'] ?? '', ENT_QUOTES); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="inputquantite" class="form-label">Quantité</label>
                <input type="text" class="form-control" id="inputquantite" name="inputquantite"
                    value="<?php echo htmlspecialchars($row['quantite'] ?? '', ENT_QUOTES); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="inputpu" class="form-label">Prix Unitaire</label>
                <input type="text" class="form-control" id="inputpu" name="inputpu"
                    value="<?php echo htmlspecialchars($row['prix_unitaire'] ?? '', ENT_QUOTES); ?>" readonly>
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-primary" onclick="window.history.back();">Retour</button>
            </div>
        </form>
    </div>
</main>
<?php
include_once("footer.php");
?>