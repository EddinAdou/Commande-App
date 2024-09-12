<?php
$commande = true;
$client = true;

include_once("header.php");
include_once("main.php");
$count = 0;
$list = [];

// Requête pour récupérer les commandes qui sont associées à des lignes de commande
$query = "SELECT idcommande 
FROM commande 
WHERE idcommande IN (
    SELECT idcommande 
    FROM ligne_commande 
    WHERE commande.idcommande = ligne_commande.idcommande
)";
if (isset($pdo)) {
    $pdostmt = $pdo->prepare($query);
}
$pdostmt->execute();
foreach ($pdostmt->fetchAll(PDO::FETCH_NUM) as $tabvalue) {
    foreach ($tabvalue as $tabelements) {
       $list[] = $tabelements;
    }
}

// Requête pour récupérer les commandes avec les noms des clients associés
$query = "
    SELECT commande.idcommande, commande.idclient, commande.date, client.nom 
    FROM commande 
    JOIN client ON commande.idclient = client.idclient
";
$pdostmt = $pdo->prepare($query);
$pdostmt->execute();
?>

<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5"> Commande </h1>
        <a href="addCommande.php" class="btn btn-primary" style="float: right;margin-bottom: 20px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-plus" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5" />
            <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
        </svg>
        </a>

        <table id="datatable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Client</th>
                    <th>Nom Client</th>
                    <th>Date</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)) :
                    $count++;
                ?>
                    <tr>
                        <td><?php echo isset($ligne["idcommande"]) ? $ligne["idcommande"] : ''; ?></td>
                        <td><?php echo isset($ligne["idclient"]) ? $ligne["idclient"] : ''; ?></td>
                        <td><?php echo isset($ligne["nom"]) ? $ligne["nom"] : ''; ?></td>
                        <td><?php echo isset($ligne["date"]) ? $ligne["date"] : ''; ?></td>
                        <td>
                            <a href="updatedcommande.php?id=<?php echo $ligne["idcommande"] ?>" class="btn btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" <?php if (in_array($ligne["idcommande"],$list)){echo "disabled";} ?> data-bs-target="#deleteModal<?php echo $count ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal<?php echo $count ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleeModalLabel">Suppression</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Voulez-vous vraiment supprimer cette commande?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <a href="deletedcommande.php?id=<?php echo  $ligne["idcommande"] ?>" type="button" class="btn btn-danger">Supprimer</a>
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