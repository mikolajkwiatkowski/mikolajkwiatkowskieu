<?php
$user_id = $_SESSION['user_id'];
// Dane do połączenia z bazą danych
$dbhost = "localhost"; 
$dbuser = "serwer305998_z4"; 
$dbpassword = "Zadankonr4!"; 
$dbname = "serwer305998_z4";

// Połączenie z bazą danych
$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

// Sprawdzenie połączenia
if (!$polaczenie) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

// Sprawdzenie, czy dane zostały przesłane metodą POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $adres = mysqli_real_escape_string($polaczenie, $_POST['adres']);
    $port = mysqli_real_escape_string($polaczenie, $_POST['port']);

    // Zapytanie do usunięcia rekordu z tabeli 'domeny' na podstawie hosta i portu
    $sql = "DELETE FROM domeny WHERE host = '$adres' AND port = '$port'";

    // Wykonanie zapytania i obsługa błędów
    if (mysqli_query($polaczenie, $sql)) {
        // Przekierowanie do innej strony po udanym usunięciu rekordu
        header('Location: polecenie1_2.php');
        exit();
    } else {
        // Wyświetlenie błędu w przypadku niepowodzenia
        echo "Błąd: " . $sql . "<br>" . mysqli_error($polaczenie);
    }
}

// Zamknięcie połączenia z bazą danych
mysqli_close($polaczenie);
?>
