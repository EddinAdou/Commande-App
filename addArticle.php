<?php
$article = true;
include_once("main.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST["inputdesc"]) && !empty($_POST["inputpu"]) && !empty($_POST["inputquantite"])) {

        // Vérification des images téléchargées
        $uploadOk = true;
        $totalSize = 0;
        if (!empty($_FILES["inputimage"]["name"][0])) {
            if (!is_dir("images")) {
                mkdir("images");
            }

            $validExtensions = ["jpg", "jpeg", "png", "webp", "gif"];
            foreach ($_FILES["inputimage"]["name"] as $index => $imageName) {
                $extension = pathinfo($imageName, PATHINFO_EXTENSION);
                $fileSize = $_FILES["inputimage"]["size"][$index];
                $totalSize += $fileSize;

                if (!in_array($extension, $validExtensions)) {
                    echo "<div class='alert alert-danger'>Le format de l'image $imageName n'est pas autorisé</div>";
                    $uploadOk = false;
                }

                if ($fileSize > 1000000) { // Vérifie que chaque image ne dépasse pas 1Mo
                    echo "<div class='alert alert-danger'>La taille de l'image $imageName dépasse la taille autorisée (1 Mo)</div>";
                    $uploadOk = false;
                }
            }

            if ($totalSize > 1000000) {
                echo "<div class='alert alert-danger'>La taille totale des images dépasse la taille autorisée (1 Mo)</div>";
                $uploadOk = false;
            }
        } else {
            echo "<div class='alert alert-danger'>Veuillez sélectionner au moins une image</div>";
            $uploadOk = false;
        }

        // Insertion de l'article et des images si tout est OK
        if ($uploadOk) {
            $query = "INSERT INTO article (description, quantite, prix_unitaire) VALUES (:desc, :quantite, :pu)";
            $pdostmt = $pdo->prepare($query);
            if ($pdostmt->execute([
                "desc" => $_POST["inputdesc"],
                "quantite" => $_POST["inputquantite"],
                "pu" => $_POST["inputpu"],
            ])) {
                $lastInsertId = $pdo->lastInsertId();

                foreach ($_FILES["inputimage"]["name"] as $index => $imageName) {
                    $path = "images/" . time() . "_" . $imageName;
                    $upload = move_uploaded_file($_FILES["inputimage"]["tmp_name"][$index], $path);
                    if ($upload) {
                        $query2 = "INSERT INTO image (urlImage, tailleImage, idarticle, descImage) VALUES (:urlImage, :tailleImage, :idarticle, :descImage)";
                        $pdostmt2 = $pdo->prepare($query2);
                        $pdostmt2->execute([
                            "urlImage" => $path,
                            "tailleImage" => $_FILES["inputimage"]["size"][$index],
                            "idarticle" => $lastInsertId,
                            "descImage" => $_POST["inputdesc"],
                        ]);
                    } else {
                        echo "<div class='alert alert-danger'>Erreur lors du téléchargement de l'image $imageName</div>";
                    }
                }

                // Redirection après insertion réussie
                echo "<div class='alert alert-success'>Article ajouté avec succès</div>";
                header("Location: articles.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Erreur lors de l'ajout de l'article</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs obligatoires.</div>";
    }
}
?>

<?php include_once("header.php"); ?>
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5">Ajouter un article</h1>

        <form class="row g-3" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="MAX-FILE-SIZE" value="1000000"/>
            <div class="col-md-6">
                <label for="inputdesc">Description</label>
                <textarea class="form-control" placeholder="Mettre la description ici" id="inputdesc" name="inputdesc" required></textarea>
            </div>
            <div class="col-md-6">
                <label for="inputquantite" class="form-label">Quantite</label>
                <input type="text" class="form-control" id="inputquantite" name="inputquantite" placeholder="quantite" required>
            </div>
            <div class="col-md-6">
                <label for="inputpu" class="form-label">PU</label>
                <input type="text" class="form-control" id="inputpu" name="inputpu" placeholder="Prix unitaire" required>
            </div>
            <div class="col-md-12">
                <label for="inputimage" class="form-label">Ajouter vos images</label>
                <input type="file" class="form-control" id="inputimage" name="inputimage[]" multiple required> <br>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</main>

<?php include_once("footer.php"); ?>
