<?php
session_start();

// Sprawdzamy, czy użytkownik jest zalogowany
if (!isset($_SESSION['loggedin'])) {
    header('Location: Zaloguj.php');
    exit();
}

$login = $_SESSION['login'];  // Pobieramy login użytkownika z sesji
$target_dir = "usersCatalogs/" . $login . "/";  // Katalog użytkownika

$currentDir = isset($_POST['currentDir']) ? $userDir . $_POST['currentDir'] . '/' : $userDir; // Jeśli jest podkatalog, dodajemy go do ścieżki
// Pełna ścieżka do pliku
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

// Sprawdzamy, czy plik jest poprawny
if (isset($_POST["submit"])) {
    $uploadOk = 1; 
    if (file_exists($target_file)) {
        echo "<script>alert('Błąd: Plik już istnieje');</script>";
        $uploadOk = 0;
    }


    // Sprawdzamy, czy zmienna $uploadOk nie została ustawiona na 0 przez jakiś błąd
    if ($uploadOk == 0) {
        echo "Plik nie został przesłany.";
    } else {
        // Jeśli wszystkie warunki są spełnione, próbujemy przesłać plik
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            header("Location: panelZalogowany.php");
        } else {
            echo "<script>alert('Wystąpił błąd');</script>";
        }
    }
}
?>
