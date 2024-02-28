<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "programma";
$password = "12345";
$dbname = "formula_one_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$selectedTable = isset($_POST['selected_table']) ? $_POST['selected_table'] : 'circuiti';
$filterKeyword = isset($_POST['filter_keyword']) ? $_POST['filter_keyword'] : '';

$columns = array();
$editRow = array();

$columns = getColumns($selectedTable);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedTable = isset($_POST['selected_table']) ? $_POST['selected_table'] : 'circuiti';
    $filterKeyword = isset($_POST['filter_keyword']) ? $_POST['filter_keyword'] : '';

    $columns = getColumns($selectedTable);

    if (isset($_POST['insert_data'])) {
        $values = array();

        foreach ($columns as $column) {
            $values[] = isset($_POST[$column]) ? $_POST[$column] : '';
        }

        $insertQuery = "INSERT INTO $selectedTable (" . implode(', ', $columns) . ") VALUES ('" . implode("', '", $values) . "');";
        $conn->query($insertQuery);

        $selectedTable = $_POST['selected_table'];
    }

    if (isset($_POST['delete_row'])) {
        $deleteRow = json_decode($_POST['delete_row'], true);

        $whereClause = array();
        foreach ($deleteRow as $column => $value) {
            $whereClause[] = "$column = '$value'";
        }

        $deleteQuery = "DELETE FROM $selectedTable WHERE " . implode(' AND ', $whereClause);
        $conn->query($deleteQuery);
    }

    if (isset($_POST['edit_row'])) {
        $editRow = json_decode($_POST['edit_row'], true);

        $columns = getColumns($selectedTable);

        if (is_array($columns) && count($columns) > 0) {
            echo "<form method='post' action='{$_SERVER['PHP_SELF']}'>";
            echo "<input type='hidden' name='selected_table' value='$selectedTable'>";

            foreach ($columns as $column) {
                $value = isset($editRow[$column]) ? $editRow[$column] : '';
                echo "<label for='$column'>$column:</label>";
                echo "<input type='text' name='$column' value='$value'>";
            }

            echo "<input type='hidden' name='edit_row_info' value='" . htmlentities(json_encode($editRow)) . "'>";
            echo "<input type='hidden' name='selected_table_for_edit' value='$selectedTable'>";

            echo "<input type='submit' name='update_data' value='Aggiorna'>";
            echo "</form>";
        } else {
            echo "Errore: Impossibile ottenere le colonne dalla tabella $selectedTable.";
        }
    }

    if (isset($_POST['update_data'])) {
        $editRow = json_decode($_POST['edit_row_info'], true);

        $updateData = array();

        foreach ($columns as $column) {
            $updateData[$column] = isset($_POST[$column]) ? $_POST[$column] : '';
        }

        $updateQuery = "UPDATE $selectedTable SET ";
        foreach ($updateData as $column => $value) {
            $updateQuery .= "$column = '$value', ";
        }
        $updateQuery = rtrim($updateQuery, ', ');
        $updateQuery .= " WHERE ";

        foreach ($editRow as $column => $value) {
            $updateQuery .= "$column = '$value' AND ";
        }
        $updateQuery = rtrim($updateQuery, 'AND ');

        $conn->query($updateQuery);
    }
}

$query = "SELECT * FROM $selectedTable WHERE CONCAT_WS('',";
$query .= implode(", ", array_map(function ($column) {
    return "COALESCE($column, '')";
}, $columns));
$query .= ") LIKE '%$filterKeyword%';";

$result = $conn->query($query);

$showTablesQuery = "SHOW TABLES FROM $dbname WHERE Tables_in_$dbname NOT LIKE 'utenti';";
$showTablesResult = $conn->query($showTablesQuery);

$tables = array();

if ($showTablesResult->num_rows > 0) {
    while ($row = $showTablesResult->fetch_row()) {
        $tables[] = $row[0];
    }
}

function getColumns($table)
{
    global $conn;
    $columns = array();

    $result = $conn->query("SHOW COLUMNS FROM $table;");

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
    }

    return $columns;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 10px auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        select,
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"],
        input[type="reset"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #45a049;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        a {
            display: block;
            text-align: center;
            color: #3498db;
            text-decoration: none;
            margin-top: 10px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h2>Benvenuto, <?php echo $_SESSION['username']; ?></h2>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="selected_table">Seleziona la tabella:</label>
        <select name="selected_table">
            <?php
            foreach ($tables as $table) {
                echo "<option value=\"$table\"";
                if ($selectedTable == $table) {
                    echo " selected";
                }
                echo ">$table</option>";
            }
            ?>
        </select>

        <label for="filter_keyword">Filtro:</label>
        <input type="text" name="filter_keyword" value="<?php echo $filterKeyword; ?>">

        <input type="submit" value="Seleziona">
    </form>

    <?php
    if ($result && $result->num_rows > 0) {
        echo "<table>
                <tr>";

        foreach ($columns as $column) {
            echo "<th>" . $column . "</th>";
        }

        echo "<th>Azioni</th>";

        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }

            echo "<td><form method='post' action='{$_SERVER['PHP_SELF']}' onsubmit='return confirm(\"Sei sicuro di voler eliminare questa riga?\")'>
                        <input type='hidden' name='selected_table' value='$selectedTable'>
                        <input type='hidden' name='delete_row' value='" . htmlentities(json_encode($row)) . "'>
                        <input type='submit' value='Elimina'>
                      </form></td>";

            echo "<td><form method='post' action='{$_SERVER['PHP_SELF']}'>
                        <input type='hidden' name='selected_table' value='$selectedTable'>
                        <input type='hidden' name='edit_row' value='" . htmlentities(json_encode($row)) . "'>
                        <input type='submit' value='Modifica'>
                      </form></td>";

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
        foreach ($columns as $column) {
            echo "<label for=\"$column\">$column:</label>";
            echo "<input type=\"text\" name=\"$column\">";
        }
        echo "<input type=\"submit\" name=\"insert_data\" value=\"Inserisci\">";
        ?>
    </form>

    <a href="login.html">Logout</a>
</body>

</html>
