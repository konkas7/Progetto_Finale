<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
</head>
<body>
    <h2>Registrazione</h2>
    <form action="register.php" method="post">
        <label for="new_username">Nuovo Username:</label>
        <input type="text" id="new_username" name="new_username" required><br>

        <label for="new_password">Nuova Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>

        <input type="submit" value="Registrati">
    </form>
</body>
</html>


<?php
$servername = "localhost";
$username = "programma";
$password = "12345";
$dbname = "formula_one_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST["new_username"];
    $new_password = $_POST["new_password"];

    // Verifica se l'utente esiste già
    $check_user_sql = "SELECT * FROM utenti WHERE username='$new_username'";
    $check_user_result = $conn->query($check_user_sql);

    if ($check_user_result->num_rows > 0) {
        echo "Username già in uso, scegline un altro.";
    } else {
        // Registra il nuovo utente
        $register_sql = "INSERT INTO utenti (username, password) VALUES ('$new_username', '$new_password')";
        if ($conn->query($register_sql) === TRUE) {
            echo "Registrazione avvenuta con successo.";
            header("Location: login.html");
        } else {
            echo "Errore durante la registrazione: " . $conn->error;
        }
    }
}

$conn->close();
?>
