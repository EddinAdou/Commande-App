<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<?php
$client = true;
include_once("main.php");
include_once("Connexion.php"); // Assuming Connexion.php defines $pdo

$query2 = "SELECT * FROM departement";
$pdostmt2 = $pdo->prepare($query2);
$pdostmt2->execute();

if (!empty($_POST["inputnom"]) && !empty($_POST["inputville"]) && !empty($_POST["inputtel"])) {
    $insertQuery = "INSERT INTO client (nom, ville_id, telephone) VALUES (:nom, :ville, :tel)";
    if (isset($pdo)) {
        $pdostmt = $pdo->prepare($insertQuery);
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

$count = 0;
$list = [];
$selectClientIdsQuery = "SELECT idclient FROM client WHERE idclient IN (SELECT idclient FROM commande WHERE commande.idclient = client.idclient)";
$pdostmt = $pdo->prepare($selectClientIdsQuery);
$pdostmt->execute();
foreach ($pdostmt->fetchAll(PDO::FETCH_NUM) as $tabvalue) {
    foreach ($tabvalue as $tabelements) {
        $list[] = $tabelements;
    }
}
?>
<?php
include_once("header.php");
?>

<script>
    $(document).ready(function () {
        $("#inputdepart").on("change", function () {
            var departement_code = $(this).val();
            $.ajax({
                url: "getVilles.php",
                type: "POST",
                data: {
                    departement_code: departement_code
                },
                success: function (data) {
                    $("#inputville").html(data);
                }
            });
        });
    });
    $("#clientForm").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: "getVilles.php",
            type: "POST",
            data: $("#clientForm").serialize(),
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.value) {
                    toastr.success(response.message);
                    // location.reload();
                } else {
                    toastr.error(response.message);

                }
            }
        })
    });
</script>
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5">Clients</h1>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo"  style="float:right;margin-bottom: 2.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
            </svg>
        </button>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter un client</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form class="row g-3" method="POST" id="clientForm">
                        <div class="modal-body">
                            <div class="col-md-6">
                                <label for="inputnom" class="form-label">nom</label>
                                <input type="text" class="form-control" id="inputnom" name="inputnom" required>
                            </div>
                            <div class="col-md-6">
                                <label for="inputtel" class="form-label">telephone</label>
                                <input type="text" class="form-control" id="inputtel" name="inputtel" required>
                            </div>
                            <div class="col-md-6">
                                <label for="inputdepart" class="form-label">Departement</label>
                                <select type="text" class="form-control" id="inputdepart" name="inputdepart" required>
                                    <option value="">Selectionner un departement(1)</option>

                                    <?php  while ($row = $pdostmt2->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo $row["departement_code"]; ?>"><?php echo $row["departement_nom"]; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="inputville" class="form-label">ville</label>
                                <select type="text" class="form-control" id="inputville" name="inputville" required>
                                    <option value="">Selectionner une ville (2)</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
        $selectClientsQuery = "SELECT client.*, villes_france.ville_nom, departement.departement_nom FROM client LEFT JOIN villes_france ON client.ville_id = villes_france.ville_id LEFT JOIN departement ON villes_france.ville_departement = departement.departement_code";
        $pdostmt = $pdo->prepare($selectClientsQuery);
        $pdostmt->execute();
        ?>

        <table id="datatable" class="display">
            <thead>
            <tr>
                <th>ID</th>
                <th>NOM</th>
                <th>TELEPHONE</th>
                <th>DEPARTEMENT</th>
                <th>VILLE</th>
                <th>ACTION</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)) :
                $count++;
                ?>
                <tr>
                    <td><?php echo $ligne["idclient"] ?? ''; ?></td>
                    <td><?php echo $ligne["nom"] ?? ''; ?></td>
                    <td><?php echo $ligne["telephone"] ?? ''; ?></td>
                    <td><?php echo $ligne["departement_nom"] ?? ''; ?></td>
                    <td><?php echo $ligne["ville_nom"] ?? ''; ?></td>
                    <td>
                        <a href="updated.php?id=<?php echo $ligne["idclient"] ?>" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"<?php if (in_array($ligne["idclient"],$list)){echo "disabled";} ?> data-bs-target="#deleteModal<?php echo $count?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                            </svg>
                        </button>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="deleteModal<?php echo $count?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleeModalLabel">Suppression</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Voulez-vous vraiment supprimer ce client?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <a href="deleted.php?id=<?php echo  $ligne["idclient"]?>" type="button" class="btn btn-danger">Supprimer</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php
include_once("footer.php");
?>