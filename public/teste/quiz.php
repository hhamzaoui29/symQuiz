<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qcm_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Sélectionner une question aléatoire
$sql = "SELECT * FROM questions ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $question = $row["question_text"];
    $reponse = $row["correct_answer"];

    // Afficher la question
    echo "<h2>$question</h2>";

    // Sélectionner toutes les options de réponse pour cette question
    $sql = "SELECT option_text FROM reponses WHERE question_id = " . $row["id"];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<form method='post' action='verifier_reponse.php'>";
        while ($row = $result->fetch_assoc()) {
            $option = $row["option_text"];
            echo "<input type='radio' name='reponse' value='$option'>$option<br>";     
        }
        echo "<input type='hidden' name='question_id' value=''>";
        echo "<input type='submit' value='Vérifier la réponse'>";
        echo "</form>";
    }
} else {
    echo "Aucune question trouvée.";
}

// Fermer la connexion à la base de données
$conn->close();
?>

