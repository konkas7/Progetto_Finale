<?php
// Verifica la sessione utente per garantire l'accesso autorizzato
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Connessione al database
$servername = "localhost";
$username = "programma";
$password = "12345";
$dbname = "formula_one_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Gestione della selezione della tabella
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedTable = $_POST['selected_table'];
    $filterKeyword = $_POST['filter_keyword'];
} else {
    // Default: mostra la prima tabella e nessun filtro
    $selectedTable = 'circuiti';
    $filterKeyword = '';
}

// Esegui la query per ottenere i dati dalla tabella selezionata con filtro
$query = "SELECT * FROM $selectedTable WHERE CONCAT_WS('',";
$query .= implode(", ", array_map(function($column) { return "COALESCE($column, '')"; }, getColumns($selectedTable)));
$query .= ") LIKE '%$filterKeyword%';";

$result = $conn->query($query);

// Esegui la query per ottenere la lista di tutte le tabelle nel database (escludendo 'utenti')
$showTablesQuery = "SHOW TABLES FROM $dbname WHERE Tables_in_$dbname NOT LIKE 'utenti';";
$showTablesResult = $conn->query($showTablesQuery);

$tables = array();

if ($showTablesResult->num_rows > 0) {
    while ($row = $showTablesResult->fetch_row()) {
        $tables[] = $row[0];
    }
}

function getColumns($table) {
    global $conn;
    $columns = array();

    $result = $conn->query("SHOW COLUMNS FROM $table;");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
    }

    return $columns;
}

// Gestione dell'inserimento di una nuova riga
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert_row'])) {
    $insertValues = $_POST['insert_values'];
    
    // Pulisci i valori inseriti
    $cleanInsertValues = array_map(function($value) use ($conn) {
        return "'" . $conn->real_escape_string($value) . "'";
    }, $insertValues);
    
    // Esegui la query per l'inserimento dei nuovi dati
    $insertQuery = "INSERT INTO $selectedTable (" . implode(", ", getColumns($selectedTable)) . ") VALUES (" . implode(", ", $cleanInsertValues) . ");";
    $insertResult = $conn->query($insertQuery);
    
    if ($insertResult) {
        echo "Nuova riga inserita con successo.";
    } else {
        echo "Errore nell'inserimento della nuova riga: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
</head>
<body>
    <h2>Benvenuto, <?php echo $_SESSION['username']; ?></h2>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="selected_table">Seleziona la tabella:</label>
        <select name="selected_table">
            <?php
            // Genera le opzioni per tutte le tabelle nel database (escludendo 'utenti')
            foreach ($tables as $table) {
                echo "<option value=\"$table\" " . ($selectedTable == $table ? 'selected' : '') . ">$table</option>";
            }
            ?>
        </select>

        <label for="filter_keyword">Filtro:</label>
        <input type="text" name="filter_keyword" value="<?php echo $filterKeyword; ?>">

        <input type="submit" value="Seleziona">
    </form>

    <?php
    if ($result->num_rows > 0) {
        // Visualizza i risultati in una tabella
        echo "<table border='1'>
                <tr>";

        // Ottieni i nomi delle colonne
        $columns = getColumns($selectedTable);
        foreach ($columns as $column) {
            echo "<th>" . $column . "</th>";
        }

        echo "</tr>";

        // Visualizza i dati della tabella
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Nessun risultato trovato per la tabella $selectedTable.";
    }
    ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h3>Inserisci nuova riga:</h3>
        <?php
        // Genera campi di input per i valori delle colonne
        foreach ($columns as $column) {
            echo "<label for='insert_values[$column]'>$column:</label>";
            echo "<input type='text' name='insert_values[$column]'>";
        }
        ?>
        <input type="hidden" name="selected_table" value="<?php echo $selectedTable; ?>">
        <input type="submit" name="insert_row" value="Inserisci">
    </form>

    <a href="login.html">Logout</a>
</body>
</html>
