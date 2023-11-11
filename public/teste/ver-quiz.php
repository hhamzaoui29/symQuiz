
<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qcm_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reponse_utilisateur = $_POST["reponse"]; // Récupère la réponse de l'utilisateur

    // Récupérer la réponse correcte depuis la base de données
    $sql = "SELECT correct_answer FROM questions WHERE id = " . $_POST["question_id"];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $reponse_correcte = $row["reponse"];

        // Vérifier si la réponse de l'utilisateur est correcte
        if ($reponse_utilisateur == $reponse_correcte) {
            echo "Bonne réponse !";
        } else {
            echo "Mauvaise réponse. La réponse correcte est : $reponse_correcte";
        }
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
