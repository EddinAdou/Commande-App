<?php
    $client=true;
    include_once("header.php");
    include_once("main.php");

    if (!empty($_POST["inputnom"]) && !empty($_POST["inputville"]) && !empty($_POST["inputtel"])) {
        $query = "INSERT INTO client (nom, ville, telephone) VALUES (:nom, :ville, :tel)";
        $pdostmt = $pdo->prepare($query);
        if ($pdostmt->execute([
            "nom" => $_POST["inputnom"],
            "ville" => $_POST["inputville"],
            "tel" => $_POST["inputtel"]
        ])) {
            echo "<div class='alert alert-success'>Client ajouté avec succès</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de l'ajout du client</div>";
        }
        $pdostmt->closeCursor();
    }
    
    
?>
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
            <!-- <div class="col-12">
    <label for="inputAddress2" class="form-label">Address 2</label>
    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
  </div>
  <div class="col-md-6">
    <label for="inputCity" class="form-label">City</label>
    <input type="text" class="form-control" id="inputCity">
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">State</label>
    <select id="inputState" class="form-select">
      <option selected>Choose...</option>
      <option>...</option>
    </select>
  </div>
  <div class="col-md-2">
    <label for="inputZip" class="form-label">Zip</label>
    <input type="text" class="form-control" id="inputZip">
  </div> -->
            <!-- <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck">
      <label class="form-check-label" for="gridCheck">
        Check me out
      </label>
    </div>
  </div> -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</main>

<?php
    include_once("footer.php");
?>