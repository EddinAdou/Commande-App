<?php
include_once("main.php");

// Vérification des paramètres pour s'assurer qu'ils existent
if (!empty($_GET["idImage"]) && !empty($_GET["id"])) {
    // Debug : afficher les paramètres reçus
    // var_dump($_GET["idImage"], $_GET["id"]);

    // Récupération des informations de l'image
    $query1 = "SELECT * FROM image WHERE idimage = :id";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute(["id" => $_GET["idImage"]]);
    $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

    if ($row1) {
        // Supprimer le fichier image physiquement si le fichier existe
        if (file_exists($row1["urlImage"])) {
            if (!unlink($row1["urlImage"])) {
                echo "<div class='alert alert-danger'>Erreur lors de la suppression du fichier sur le serveur.</div>";
                exit();
            } else {
                echo "<div class='alert alert-success'>Le fichier a été supprimé avec succès.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Le fichier n'existe pas sur le serveur. Suppression uniquement dans la base de données.</div>";
        }

        // Supprimer l'entrée de l'image dans la base de données
        $query2 = "DELETE FROM image WHERE idimage = :id";
        $stmt2 = $pdo->prepare($query2);

        try {
            if ($stmt2->execute(["id" => $_GET["idImage"]])) {
                // Redirection vers la page de modification de l'article après suppression
                header("Location: updatedArticle.php?id=" . $_GET["id"]);
                exit();
            } else {
                echo "<div class='alert alert-danger'>Erreur lors de la suppression de l'image dans la base de données.</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erreur PDO : " . htmlspecialchars($e->getMessage()) . "</div>";
        }

        // Fermer les curseurs de requêtes
        $stmt1->closeCursor();
        $stmt2->closeCursor();
    } else {
        echo "<div class='alert alert-danger'>L'image n'a pas été trouvée dans la base de données.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>ID de l'image ou de l'article non fourni.</div>";
}
?>
