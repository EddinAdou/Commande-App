<?php
    $commande=true;
    $client=true;
    
    include_once("main.php");

    if (!empty($_POST["inputnom"]) && !empty($_POST["inputville"]) && !empty($_POST["inputtel"])) {
        $query = "INSERT INTO client (nom, ville, telephone) VALUES (:nom, :ville, :tel)";
        if (isset($pdo)) {
            $pdostmt = $pdo->prepare($query);
        }
        if ($pdostmt->execute([
            "nom" => $_POST["inputnom"],
            "ville" => $_POST["inputville"],
            "tel" => $_POST["inputtel"]
        ])) {
            $lastInsertId = $pdo->lastInsertId();
            header("Location: clients.php?id=" . $lastInsertId);
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
        <h1 class="mt-5">Ajouter un client</h1>

        <form class="row g-3" method="POST">
            <div class="col-md-6">
                <label for="inputnom" class="form-label">nom</label>
                <input type="text" class="form-control" id="inputnom" name="inputnom" required>
            </div>
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">ville</label>
                <input type="text" class="form-control" id="inputville" name="inputville" required>
            </div>
            <div class="col-12">
                <label for="inputAddress" class="form-label">telephone</label>
                <input type="text" class="form-control" id="inputtel" name="inputtel" required>
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