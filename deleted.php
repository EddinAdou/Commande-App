<?php
include_once("main.php");

if (!empty($_GET["id"])) {
    $query = "DELETE FROM client WHERE idclient = :id";
    $objstmt = $pdo->prepare($query);
    
    if ($objstmt->execute(["id" => $_GET["id"]])) {
        // Suppression réussie, redirection vers la page client.php
        header("Location: clients.php");
        exit(); // Toujours suivre un header() par exit() pour s'assurer que le script s'arrête
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la suppression du client</div>";
    }
    
    $objstmt->closeCursor();
}
?>
