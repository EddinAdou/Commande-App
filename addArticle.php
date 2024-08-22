<?php
    $article=true;
    
    include_once("main.php");

    if (!empty($_POST["inputdesc"]) && !empty($_POST["inputpu"])) {
        $query = "INSERT INTO article (description,prix_unitaire) VALUES (:desc,:pu)";
        $pdostmt = $pdo->prepare($query);
        if ($pdostmt->execute([
            "desc" => $_POST["inputdesc"],
            "pu" => $_POST["inputpu"],
          
        ])) {
            $lastInsertId = $pdo->lastInsertId();
            header("Location: articles.php?id=" . $lastInsertId);
            exit();
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de l'ajout de l'article</div>";
        }
        $pdostmt->closeCursor();
    }
    
    
?>
<?php include_once("header.php"); ?>
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5">Ajouter un article</h1>

        <form class="row g-3" method="POST">
            <div class="col-md-6">
                <label for="inputdesc">Description</label>
                <textarea class="form-control" placeholder="Mettre la description ici" id="inputdesc"
                    name="inputdesc" required></textarea>
                

            </div>
            <div class="col-md-6">
                <label for="inputpu" class="form-label">PU</label>
                <input type="text" class="form-control" id="inputpu" name="inputpu" placeholder="Prix unitaire"
                    required>
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