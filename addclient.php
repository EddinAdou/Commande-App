<?php
$commande=true;
$client=true;

include_once("main.php");



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
</script>

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
                <label for="inputtel" class="form-label">telephone</label>
                <input type="text" class="form-control" id="inputtel" name="inputtel" required>
            </div>
            <div class="col-md-6">
                <label for="inputdepart" class="form-label">Departement</label>
                <select type="text" class="form-control" id="inputdepart" name="inputdepart" required>
                    <option value="">Selectionner un departement(1)</option>

                    <?php  while ($row = $pdostmt2->fetch(PDO::FETCH_ASSOC)):
                ?>
                    <option value="<?php echo $row["departement_code"]; ?>"><?php echo $row["departement_nom"]; ?>
                    </option>

                <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="inputville" class="form-label">ville</label>
                <select type="text" class="form-control" id="inputville" name="inputville" required>
                <option value="">Selectionner une ville (2)</option>
                </select>
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