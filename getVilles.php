<?php
global $pdo;
include_once("main.php");

if (!empty($_POST["departement_code"])) {
    $query = "SELECT * FROM villes_france WHERE ville_departement = :departement_code ORDER BY ville_nom ASC";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["departement_code" => $_POST["departement_code"]]);

    while ($villes = $pdostmt->fetchAll(PDO::FETCH_ASSOC)) {
        foreach ($villes as $ville) {
            echo "<option value='" . $ville["ville_id"] . "'>" . $ville["ville_nom"] . "</option>";
        }
    }
    $pdostmt->closeCursor();
} else {
    echo "<option value=''>Aucun département sélectionné</option>";
}
if (!empty($_POST["inputnom"]) && !empty($_POST["inputtel"]) && !empty($_POST["inputdepart"]) && !empty($_POST["inputville"])) {
    $query = "INSERT INTO client (nom, telephone, ville_id) VALUES (:nom, :telephone, :ville_id)";
    $pdostmt = $pdo->prepare($query);
    $result = $pdostmt->execute([
        "nom" => $_POST["inputnom"],
        "tel" => $_POST["inputtel"],
        "ville" => $_POST["inputville"]
    ]);
    if ($result){
        $response =[
            "value" => true,
            "message" => "Client ajouté avec succès!"
        ];
    } else {
        $response =[
            "value" => false,
            "message" =>$pdostmt->errorInfo()
        ];

    }
    echo json_encode($response);
    $pdostmt->closeCursor();
}
