<?php
function get_browser_info() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $browserName = 'Nieznana';
    $browserVersion = '';

    if (strpos($userAgent, 'Firefox') !== false) {
        $browserName = 'Firefox';
        preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches);
        $browserVersion = $matches[1] ?? '';
    } elseif (strpos($userAgent, 'Edg') !== false) {
        $browserName = 'Edge';
        preg_match('/Edg\/([0-9.]+)/', $userAgent, $matches);
        $browserVersion = $matches[1] ?? '';
    } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
        $browserName = 'Opera';
        preg_match('/(Opera|OPR)\/([0-9.]+)/', $userAgent, $matches);
        $browserVersion = $matches[2] ?? '';
    } elseif (strpos($userAgent, 'CriOS') !== false) { 
        $browserName = 'Chrome iOS';
        preg_match('/CriOS\/([0-9.]+)/', $userAgent, $matches);
        $browserVersion = $matches[1] ?? '';
    } elseif (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Safari') !== false) {
        $browserName = 'Chrome';
        preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches);
        $browserVersion = $matches[1] ?? '';
    } elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
        $browserName = 'Safari';
        preg_match('/Version\/([0-9.]+)/', $userAgent, $matches);
        $browserVersion = $matches[1] ?? '';
    } 

    $browserFullName = $browserName . ' ' . $browserVersion;

    return $browserFullName; // Zwracamy tylko nazwę przeglądarki
}
session_start();

$AdresIP = $_SERVER["REMOTE_ADDR"];
$DataICzas = date("Y-m-d H:i:s");
$browser = get_browser_info(); 

// Odbierz dane z JavaScript w PHP
if (isset($_POST['java']) && isset($_POST['cookies']) && isset($_POST['language']) && isset($_POST['colorDepth']) && isset($_POST['screenResolution']) && isset($_POST['windowResolution'])) {
    $javaEnabled = $_POST['java'];
    $cookiesEnabled = $_POST['cookies'];
    $language = $_POST['language'];
    $colorDepth = $_POST['colorDepth'];
    $screenResolution = $_POST['screenResolution'];  
    $windowResolution = $_POST['windowResolution'];  

}

$login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
$pass = htmlentities($_POST['password'], ENT_QUOTES, "UTF-8");

$link = mysqli_connect("localhost", "serwer305998_z5", "Zadankonr5!", "serwer305998_z5");
if (!$link) {
    echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    exit();
}

mysqli_query($link, "SET NAMES 'utf8'");

// Sprawdzenie, czy IP już istnieje w tabeli
$query = "SELECT * FROM goscieportalu WHERE AdresIP = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 's', $AdresIP);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($row) {
    // Jeśli adres IP istnieje, zaktualizuj LiczbaWejsc i datę
    $LiczbaWejsc = $row['LiczbaWejsc'] + 1;
    $updateQuery = "UPDATE goscieportalu 
                    SET DataICzas = ?, 
                        LiczbaWejsc = ?, 
                        Przeglądarka = ?, 
                        RozdzielczoscEkranu = ?, 
                        RozdzielczoscOkna = ?, 
                        IloscKolorow = ?, 
                        Ciasteczka = ?, 
                        Java = ?, 
                        Język = ?
                    WHERE AdresIP = ?";
    $updateStmt = mysqli_prepare($link, $updateQuery);
    
    mysqli_stmt_bind_param($updateStmt, 'issssissss', 
        $DataICzas, 
        $LiczbaWejsc, 
        $browser, 
        $screenResolution, 
        $windowResolution, 
        $colorDepth, 
        $cookiesEnabled, 
        $javaEnabled, 
        $language, 
        $AdresIp
    );
   
    mysqli_stmt_execute($updateStmt);
    mysqli_stmt_close($updateStmt);

} else {
    // Jeśli adres IP nie istnieje, dodaj nowy wpis
    $LiczbaWejsc = 1;
    $insertQuery = "INSERT INTO goscieportalu (AdresIP, DataICzas, LiczbaWejsc, Przeglądarka, RozdzielczoscEkranu, RozdzielczoscOkna, IloscKolorow, Ciasteczka, Java, Język) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = mysqli_prepare($link, $insertQuery);
    
    // Same bindings as before
    mysqli_stmt_bind_param($insertStmt, 'ssisssisss', 
        $AdresIP, 
        $DataICzas, 
        $LiczbaWejsc, 
        $browser, 
        $screenResolution, 
        $windowResolution, 
        $colorDepth, 
        $cookiesEnabled, 
        $javaEnabled, 
        $language
    );
    
    mysqli_stmt_execute($insertStmt);
    mysqli_stmt_close($insertStmt);
}


// Kontynuacja logowania użytkownika
$result1 = mysqli_query($link, "SELECT * FROM users WHERE login='$login'");
$rekord1 = mysqli_fetch_array($result1);

if (!$rekord1) {
    echo "<p>Nie ma takiego użytkownika!</p>";
    mysqli_close($link);
    echo "<a href='Zaloguj.php'>Spróbuj ponownie</a>";
    exit();
} else {
    if ($rekord1['password'] == $pass) {
        $_SESSION['loggedin'] = true;
        $_SESSION['login'] = $login;
        $_SESSION['avatar'] = $rekord1['avatar'];
        mysqli_close($link);
        header("Location: panelZalogowany.php");
        exit();
    } else {
        mysqli_close($link);
        header("Location: Zaloguj.php");
        exit();
    }
}
?>
