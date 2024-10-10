<?php
include_once("main.php");

$connexion = new Connexion();
$pdo = $connexion->getPDO();
// Activer les erreurs PDO pour le débogage
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!empty($_GET["id"])) {
    $query = "DELETE FROM article WHERE idarticle = :id";
    $objstmt = $pdo->prepare($query);
    
    try {
        if ($objstmt->execute(["id" => $_GET["id"]])) {
            // Suppression réussie, redirection vers la page articles.php
            header("Location: articles.php");
            exit(); // Toujours suivre un header() par exit() pour s'assurer que le script s'arrête
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de la suppression de l'article</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur PDO : " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    
    $objstmt->closeCursor();
} else {
    echo "<div class='alert alert-danger'>ID de l'article non fourni</div>";
}
?>