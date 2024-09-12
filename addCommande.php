<?php
$client=true;

include_once("main.php");
$query = "SELECT idclient FROM client";
if (isset($pdo)) {
    $objstmt = $pdo->prepare($query);
}
$objstmt->execute();

if (!empty($_POST["inputidclient"]) && !empty($_POST["inputdate"])) {
    $query = "INSERT INTO commande (idclient, date) VALUES (:idclient, :date)";
    if (isset($pdo)) {
        $pdostmt = $pdo->prepare($query);
    }
    if ($pdostmt->execute([
        "idclient" => $_POST["inputidclient"],
        "date" => $_POST["inputdate"],

    ])) {
        $lastInsertId = $pdo->lastInsertId();
        header("Location: commandes.php?id=" . $lastInsertId);
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'ajout du client</div>";
    }
    $pdostmt->closeCursor();
}


?>
<?php include_once("header.php"); ?>
    <main class="flex-shrink-0">
        <div class="container">
            <h1 class="mt-5">Ajouter une Commande</h1>

            <form class="row g-3" method="POST">
                <div class="col-md-6">
                    <label for="inputidclient" class="form-label">ID Client</label>
                    <select class="form-control" required name="inputidclient">
                        <option value="">--Choisir un client--</option>
                        <?php
                        foreach ($objstmt->fetchAll(PDO::FETCH_NUM) as $row) {
                            foreach ($row as $elmt) {
                            echo "<option value='" .$elmt. "'>" . $elmt. "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="inputdate" class="form-label">DATE</label>
                    <input type="date" class="form-control" id="inputdate" name="inputdate" required>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </main>

<?php
include_once("footer.php");
?>