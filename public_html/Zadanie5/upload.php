<?php
session_start();

// Sprawdzamy, czy użytkownik jest zalogowany
if (!isset($_SESSION['loggedin'])) {
    header('Location: Zaloguj.php');
    exit();
}

// Określamy katalog główny użytkownika
$login = $_SESSION['login'];
$userDir = "usersCatalogs/" . $login . "/";

// Jeśli użytkownik jest w podkatalogu, dodajemy go do ścieżki
$currentDir = isset($_POST['currentDir']) ? $userDir . $_POST['currentDir'] . '/' : $userDir; // Jeśli jest podkatalog, dodajemy go do ścieżki

// Pełna ścieżka do pliku
$target_file = $currentDir . basename($_FILES["fileToUpload"]["name"]);

// Sprawdzamy, czy katalog użytkownika istnieje, jeśli nie, tworzymy go
if (!file_exists($currentDir)) {
    mkdir($currentDir, 0777, true); // Tworzymy katalog, jeśli nie istnieje
}

// Sprawdzamy, czy plik już istnieje
if (file_exists($target_file)) {
    echo "<script>alert('Błąd: Plik już istnieje');</script>";
    exit();
}

// Przenosimy plik
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    header("Location: panelZalogowany.php");
} else {
    echo "<script>alert('Wystąpił błąd podczas przesyłania pliku');</script>";
}
?>
