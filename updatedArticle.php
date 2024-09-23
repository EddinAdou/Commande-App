<?php
include_once("main.php");

if (!empty($_POST)) {
    $query1 = "UPDATE article SET description = :description, quantite = :quantite, prix_unitaire = :prix_unitaire WHERE idarticle = :id";
    $pdostmt1 = $pdo->prepare($query1);
    $pdostmt1->execute([
        "description" => $_POST["inputdesc"],
        "quantite" => $_POST["inputquantite"],
        "prix_unitaire" => $_POST["inputpu"],
        "id" => $_GET["id"]
    ]);
    $pdostmt1->closeCursor();

    if (!empty($_FILES["inputimage"]["size"]) && array_sum($_FILES["inputimage"]["size"]) < 1000000) {
        if (!is_dir("images")) {
            mkdir("images");
        }

        $validExtensions = ["jpg", "jpeg", "png", "webp", "gif"];
        foreach ($_FILES["inputimage"]["name"] as $index => $imageName) {
            $extension = pathinfo($imageName, PATHINFO_EXTENSION);
            if (!in_array($extension, $validExtensions)) {
                echo "<div class='alert alert-danger'>Le format de l'image $imageName n'est pas autorisé</div>";
            } else {
                $path = "images/" . time() . "_" . $imageName;
                $upload = move_uploaded_file($_FILES["inputimage"]["tmp_name"][$index], $path);
                if ($upload) {
                    $query2 = "INSERT INTO image (urlImage, tailleImage, idarticle, descImage) VALUES (:urlImage, :tailleImage, :idarticle, :descImage)";
                    $pdostmt2 = $pdo->prepare($query2);
                    $pdostmt2->execute([
                        "urlImage" => $path,
                        "tailleImage" => $_FILES["inputimage"]["size"][$index],
                        "idarticle" => $_GET["id"],
                        "descImage" => $_POST["inputdesc"]
                    ]);
                } else {
                    echo "<div class='alert alert-danger'>Erreur lors du téléchargement de l'image $imageName</div>";
                }
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Aucune image ou taille d'image incorrecte</div>";
    }
    header('Location: articles.php');
    exit();
}

if (!empty($_GET)) {
    $query1 = "SELECT * FROM article WHERE idarticle = :id";
    $pdostmt1 = $pdo->prepare($query1);
    $pdostmt1->execute(["id" => $_GET["id"]]);
    $row1 = $pdostmt1->fetch(PDO::FETCH_ASSOC);

    $query2 = "SELECT * FROM image WHERE idarticle = :id";
    $pdostmt2 = $pdo->prepare($query2);
    $pdostmt2->execute(["id" => $_GET["id"]]);
}
?>

<?php include_once("header.php"); ?>
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5">Modifier un article</h1>
        <form class="row g-3" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
            <div class="col-md-6">
                <label for="inputdesc">Description</label>
                <textarea class="form-control" placeholder="Mettre la description ici" id="inputdesc" name="inputdesc" required><?php echo htmlspecialchars($row1["description"]); ?></textarea>
            </div>
            <div class="col-md-6">
                <label for="inputquantite" class="form-label">Quantite</label>
                <input type="text" class="form-control" id="inputquantite" name="inputquantite" value="<?php echo htmlspecialchars($row1["quantite"]); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="inputpu" class="form-label">PU</label>
                <input type="text" class="form-control" id="inputpu" name="inputpu" placeholder="Prix unitaire" value="<?php echo htmlspecialchars($row1["prix_unitaire"]); ?>" required>
            </div>
            <div class="col-md-7">
                <label for="inputimage" class="form-label">Charger l'image</label>
                <input type="file" class="form-control" id="inputimage" name="inputimage[]" multiple>
            </div>
            <div class="col-md-5 images-container">
                <?php while ($row2 = $pdostmt2->fetch(PDO::FETCH_ASSOC)): ?>
                    <div style="position:relative; margin-right: 10px;">
                        <a href="deleteImage.php?id=<?php echo $row2["idarticle"]?>&idImage=<?php echo $row2["idImage"]?>" class="btn btn-outline-danger" style="position: absolute;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </a>
                        <img src="<?php echo $row2["urlImage"]; ?>" height="100" alt="Image article">
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Modifier</button>
            </div>
        </form>
    </div>
</main>
<?php
$pdostmt1->closeCursor();
$pdostmt2->closeCursor();
include_once("footer.php");
?>
