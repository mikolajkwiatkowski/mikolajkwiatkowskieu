<?php
session_start();

// Pobieranie danych z formularza i zabezpieczenie przed SQL Injection oraz XSS
$login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
$pass1 = htmlentities($_POST['password1'], ENT_QUOTES, "UTF-8");
$pass2 = htmlentities($_POST['password2'], ENT_QUOTES, "UTF-8");

// Połączenie z bazą danych
$link = mysqli_connect("localhost", "serwer305998_z5", "Zadankonr5!", "serwer305998_z5");

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
    $targetDir = "avatars/"; // Katalog, do którego będą przesyłane obrazy
    $defaultAvatar = 'avatars/default_avatar.jpg'; // Ścieżka do domyślnego awatara

    // Sprawdzenie, czy katalog istnieje, jeśli nie, to go tworzymy
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Sprawdzenie, czy plik jest obrazem
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return $defaultAvatar; // Nie jest obrazem, zwracamy domyślny avatar
    }

    // Sprawdzenie dozwolonych formatów
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        return $defaultAvatar; // Zły format, zwracamy domyślny avatar
    }

    // Przesunięcie pliku do katalogu docelowego
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile; // Zwracamy ścieżkę do przesłanego pliku
    } else {
        return $defaultAvatar; // W razie błędu przesyłania zwracamy domyślny avatar
    }
}

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
    $avatarPath = uploadAvatar($_FILES['avatar']);
} else {
    $avatarPath = 'avatars/default_avatar.jpg'; // Ustaw domyślny avatar
}

// Przygotowane zapytanie do wstawienia użytkownika z loginem, hasłem i ścieżką do avatara
$stmt = mysqli_prepare($link, "INSERT INTO users (login, password, avatar) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sss', $login, $pass1, $avatarPath);

if (mysqli_stmt_execute($stmt)) {
    echo "Użytkownik dodany pomyślnie!";
    echo "<br><a href='Zaloguj.php'>Zaloguj się</a>";
} else {
    echo "Błąd: " . mysqli_error($link);
}

// Zamknięcie połączenia
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
