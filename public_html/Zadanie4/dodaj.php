<?php
session_start();

// Pobieranie danych z formularza i zabezpieczenie przed SQL Injection oraz XSS
$login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
$pass1 = htmlentities($_POST['password1'], ENT_QUOTES, "UTF-8");
$pass2 = htmlentities($_POST['password2'], ENT_QUOTES, "UTF-8");
$isWorker = isset($_POST['isWorker']) ? 1 : 0; // Zmienna dla pola "worker" (checkbox)

// Połączenie z bazą danych
$link = mysqli_connect("localhost", "serwer305998_z4", "Zadankonr4!", "serwer305998_z4");

if (!$link) {
    echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    exit();
}

mysqli_query($link, "SET NAMES 'utf8'");

// Sprawdzenie, czy hasła są zgodne
if ($pass1 != $pass2) {
    echo "Hasła różnią się od siebie! Spróbuj jeszcze raz<br>";
    echo "<a href='Zarejestruj.php'>Wróć</a>";
    mysqli_close($link);
    exit();
}

function uploadAvatar($file) {
    $targetDir = "avatars/"; // Katalog docelowy dla awatarów
    $defaultAvatar = 'avatars/default_avatar.jpg'; // Domyślny avatar

    // Tworzenie katalogu, jeśli nie istnieje
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Sprawdzanie, czy plik jest obrazem
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return $defaultAvatar; // Zwracamy domyślny avatar, jeśli plik nie jest obrazem
    }

    // Dozwolone formaty obrazów
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        return $defaultAvatar; // Zwracamy domyślny avatar, jeśli format jest niepoprawny
    }

    // Przesuwanie pliku do katalogu docelowego
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile; // Zwracamy ścieżkę do przesłanego pliku
    } else {
        return $defaultAvatar; // Zwracamy domyślny avatar, jeśli wystąpił błąd przesyłania
    }
}

// Przesyłanie awatara, jeśli został załączony
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
    $avatarPath = uploadAvatar($_FILES['avatar']);
} else {
    $avatarPath = 'avatars/default_avatar.jpg'; // Ustawiamy domyślny avatar
}

// Przygotowane zapytanie do wstawienia użytkownika z loginem, hasłem, ścieżką do avatara i wartością pola "worker"
$stmt = mysqli_prepare($link, "INSERT INTO users (login, password, avatar, worker) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sssi', $login, $pass1, $avatarPath, $isWorker);

if (mysqli_stmt_execute($stmt)) {
    // Przekierowanie do strony logowania po pomyślnej rejestracji
    header("Location: Zaloguj.php");
    exit();
} else {
    echo "Błąd: " . mysqli_error($link);
}

// Zamknięcie połączenia
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
