<?php
$index = true;
include_once("header.php");
include_once("main.php");

// Requête pour récupérer les données
$query3 = "
    SELECT commande.idcommande, commande.idclient, commande.date, client.nom 
    FROM commande 
    JOIN client ON commande.idclient = client.idclient
";
$pdostmt3 = $pdo->prepare($query3);
$pdostmt3->execute();

$query = "
    SELECT c.nom, c.telephone, c.ville, cm.idcommande, cm.date, a.description, lc.quantite, a.prix_unitaire 
FROM client c
JOIN commande cm ON c.idclient = cm.idclient
JOIN ligne_commande lc ON cm.idcommande = lc.idcommande
JOIN article a ON lc.idarticle = a.idarticle

";


// Préparation et exécution de la requête
if (isset($pdo)) {
    $objstmt = $pdo->prepare($query);
    $objstmt->execute(); // Exécution de la requête
}
?>

<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5">Accueil</h1>
        <table id="datatable" class="display">
            <thead>
                <tr>
                    <th></th>
                    <th>NOM</th>
                    <th>TELEPHONE</th>
                    <th>VILLE</th>
                    <th>DATE</th>
                    <th>DESCRIPTION</th>
                    <th>QUANTITE</th>
                    <th>PRIX UNITAIRE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Affichage des résultats de la requête
                while ($ligne = $objstmt->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td>
                            <a href="viewaccueil.php?id=<?php echo $ligne['idcommande']; ?>" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-eye" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                    <path
                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                </svg>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($ligne["nom"]); ?></td>
                        <td><?php echo htmlspecialchars($ligne["telephone"]); ?></td>
                        <td><?php echo htmlspecialchars($ligne["ville"]); ?></td>
                        <td><?php echo htmlspecialchars($ligne["date"]); ?></td>
                        <td><?php echo htmlspecialchars($ligne["description"]); ?></td>
                        <td><?php echo htmlspecialchars($ligne["quantite"]); ?></td>
                        <td><?php echo htmlspecialchars($ligne["prix_unitaire"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
// Fermeture du curseur
$objstmt->closeCursor();
include_once("footer.php");
?>