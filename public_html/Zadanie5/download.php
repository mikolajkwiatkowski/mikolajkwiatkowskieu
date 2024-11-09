<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: Zaloguj.php');
    exit();
}

// Pobieramy dane z URL
$file = isset($_GET['file']) ? $_GET['file'] : '';
$dir = isset($_GET['dir']) ? $_GET['dir'] : '';

// Katalog użytkownika
$userDir = "usersCatalogs/" . $_SESSION['login'];

// Ścieżka do pliku
$filePath = $userDir . '/' . $dir . '/' . $file;

if (file_exists($filePath)) {
    // Wysłanie nagłówków, aby wymusić pobranie pliku
    header('Content-Description: File Transfer');
    header('Content-Type: ' . mime_content_type($filePath));
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($filePath));

    // Odczyt i wysłanie pliku
    readfile($filePath);
    exit();
} else {
    echo "Plik nie istnieje.";
}
?>
