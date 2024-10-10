<main class="flex-shrink-0">
    <div class="container">

    </div>
    <?php
    include_once("connexion.php");

    $pdo = new Connexion();
    $dsn = "mysql:host=localhost;dbname=db";
    $username = "root";
    $password = "";
    $pdo = new Connexion($dsn, $username, $password);
    ?>