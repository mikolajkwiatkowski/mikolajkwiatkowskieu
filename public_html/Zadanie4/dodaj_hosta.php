<?php
session_start();
$user_id = $_SESSION['user_id']; // Pobranie user_id z sesji

// Dane do połączenia z bazą danych
$dbhost = "localhost"; 
$dbuser = "serwer305998_z4"; 
$dbpassword = "Zadankonr4!"; 
$dbname = "serwer305998_z4";

// Połączenie z bazą danych
$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $adres = mysqli_real_escape_string($polaczenie, $_POST['adres']);
    $port = mysqli_real_escape_string($polaczenie, $_POST['port']);

    // Dodanie rekordu z user_id
    $sql = "INSERT INTO domeny (host, port, user_id) VALUES ('$adres', '$port', '$user_id')";

    if (mysqli_query($polaczenie, $sql)) {
        header('Location: polecenie1_2.php');
        exit();
    } else {
        echo "Błąd: " . $sql . "<br>" . mysqli_error($polaczenie);
    }
}

mysqli_close($polaczenie);
?>
