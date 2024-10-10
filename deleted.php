<?php
include_once("connexion.php");
include_once("main.php");

$connexion = new Connexion();
$pdo = $connexion->getPDO();
if (!empty($_GET["id"])) {
    $idclient = $_GET["id"];

    if (isset($_GET["cascade"]) && $_GET["cascade"] == "true") {
        // Supprimer les commandes associées avant de supprimer le client
        $query = "DELETE FROM commande WHERE idclient = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(["id" => $idclient]);
    }

    // Supprimer le client
    $query = "DELETE FROM client WHERE idclient = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(["id" => $idclient]);

    // Rediriger après la suppression
    header("Location: clients.php");
    exit();
}
?>
