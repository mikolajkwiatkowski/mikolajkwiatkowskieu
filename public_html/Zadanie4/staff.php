<?php
session_start();

// Sprawdzenie, czy zalogowany użytkownik to admin/admin
if (!isset($_SESSION['login']) || $_SESSION['login'] !== 'admin') {
    echo "Dostęp zabroniony: musisz być zalogowany jako administrator.";
    exit;
}

$dbhost = "localhost";
$dbuser = "serwer305998_z4";
$dbpassword = "Zadankonr4!";
$dbname = "serwer305998_z4";

// Połączenie z bazą danych
$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

// Sprawdzenie połączenia
if (!$polaczenie) {
    die("Błąd połączenia z MySQL: " . mysqli_connect_error());
}

// Pobranie listy pracowników z tabeli `users`, gdzie worker = TRUE
$workers_query = "SELECT id, login FROM users WHERE worker = TRUE";
$workers_result = mysqli_query($polaczenie, $workers_query);

// Obsługa formularza po jego przesłaniu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = mysqli_real_escape_string($polaczenie, $_POST['host']);
    $port = (int)$_POST['port'];
    $worker_id = (int)$_POST['worker'];

    // Dodanie nowego hosta do tabeli `domeny` i przypisanie go do pracownika
    $insert_query = "INSERT INTO domeny (host, port, user_id) VALUES ('$host', '$port', '$worker_id')";
    if (mysqli_query($polaczenie, $insert_query)) {
        header("Location: polecenie1_2.php");
        exit();
    } else {
        echo "Błąd przy dodawaniu hosta: " . mysqli_error($polaczenie);
    }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Dodaj hosta - Administrator</title>
    <style>
        /* Styl dla całej strony */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Kontener dla formularza */
        .form-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        /* Nagłówek */
        h2 {
            color: #333;
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        /* Styl dla etykiet i pól formularza */
        label {
            display: block;
            color: #555;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        /* Styl dla przycisku wysłania */
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Dodaj hosta do monitorowania</h2>
        <form action="staff.php" method="POST">
            <label for="host">Adres hosta:</label>
            <input type="text" id="host" name="host" required>
            
            <label for="port">Numer portu:</label>
            <input type="number" id="port" name="port" required>
            
            <label for="worker">Pracownik odpowiedzialny:</label>
            <select id="worker" name="worker" required>
                <?php
                // Wypełnienie listy pracowników (tylko tych z worker = TRUE)
                while ($row = mysqli_fetch_assoc($workers_result)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['login'] . "</option>";
                }
                ?>
            </select>
            
            <input type="submit" value="Dodaj hosta">
        </form>
    </div>

</body>

</html>


<?php
// Zamknięcie połączenia z bazą
mysqli_close($polaczenie);
?>
