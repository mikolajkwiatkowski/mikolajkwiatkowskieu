<?php
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
    // Pobranie ID rekordu do usunięcia
    $id = mysqli_real_escape_string($polaczenie, $_POST['id']);

    // Zapytanie do usunięcia rekordu z tabeli 'domeny'
    $sql = "DELETE FROM domeny WHERE id = '$id'";

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
