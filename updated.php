<?php
    $client=true;
    include_once("header.php");
    include_once("main.php");

    if (!empty($_GET["idclient"])) {
        $query = "SELECT * FROM client WHERE idclient = :id";
        $pdostmt = $pdo->prepare($query);
        $pdostmt->execute(["id"=> $_GET["id"]]); 
        while($row= $pdostmt->fetch(PDO::FETCH_ASSOC)) :
    
?>
        <main class="flex-shrink-0">
            <div class="container">
                <h1 class="mt-5">Modifier un client</h1>

                <form class="row g-3" method="POST">
                    <div class="col-md-6">
                        <label for="inputnom" class="form-label">nom</label>
                        <input type="text" class="form-control" id="inputnom" name="inputnom" value=<?php echo $row["nom"]?> required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">ville</label>
                        <input type="text" class="form-control" id="inputville" name="inputville" required>
                    </div>
                    <div class="col-12">
                        <label for="inputAddress" class="form-label">telephone</label>
                        <input type="text" class="form-control" id="inputtel" name="inputtel"  required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </main>

<?php
    endwhile;
    //$pdostmt-closeCursor();
    }
?>

<?php
    include_once("footer.php");
?>