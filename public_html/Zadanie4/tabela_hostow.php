<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    echo "Użytkownik niezalogowany!";
    exit;
}

$user_id = $_SESSION['user_id'];
$login = $_SESSION['login'];

$dbhost = "localhost";
$dbuser = "serwer305998_z4";
$dbpassword = "Zadankonr4!";
$dbname = "serwer305998_z4";

// Połączenie z bazą danych
$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

if (!$polaczenie) {
    echo "Błąd połączenia z MySQL." . PHP_EOL;
    echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

date_default_timezone_set('Europe/Warsaw');

if ($login === 'admin') {
    $rezultat = mysqli_query($polaczenie, "SELECT * FROM domeny") or die("Błąd zapytania do bazy: $dbname");
} else {
    $rezultat = mysqli_query($polaczenie, "SELECT * FROM domeny WHERE user_id='$user_id'") or die("Błąd zapytania do bazy: $dbname");
}

function secondsToTime($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Monitoring Usług Sieciowych</title>
    <style>
        /* Pulsująca animacja */
        .pulse {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Styl dla animowanego awatara */
        #assistantAvatar {
            width: 100px;
            height: 100px;
            animation: bounce 1s infinite;
            position: fixed;
            bottom: 20px;
            right: 20px;
        }

        @keyframes bounce {
            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
    </style>
    <script>
        // Automatyczne odświeżanie strony co 10 sekund
        setInterval(function () {
            location.reload();
        }, 10000); // 10000 ms = 10 sekund
    </script>
</head>

<body>
    <div class="container mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ikona</th>
                    <th>ID</th>
                    <th>Host</th>
                    <th>Port</th>
                    <th>Stan</th>
                    <th>Nieudane próby</th>
                    <th>Data i czas utraty połączenia</th>
                    <th>Łączny czas niedostępności (HH:MM:SS)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                mysqli_data_seek($rezultat, 0); // Resetowanie wskaźnika wyników

                $alert = false;
                $working_services = [];
                $not_working_services = [];

                while ($wiersz = mysqli_fetch_array($rezultat)) {
                    $id = $wiersz['id'];
                    $host = $wiersz['host'];
                    $port = $wiersz['port'];
                    $errors = $wiersz['errors'];
                    $total_errors = $wiersz['total_errors'];
                    $failure = $wiersz['failure'];
                    $downtime_seconds = $wiersz['downtime_seconds']; // Łączny czas niedostępności

                    // Sprawdzenie połączenia z hostem
                    $fp = @fsockopen($host, $port, $errno, $errstr, 30);
                    if ($fp) {
                        $state = 'Ok';
                        fclose($fp);
                        $working_services[] = "$host:$port"; // Dodanie do działających usług

                        // Resetowanie błędów, gdy połączenie jest udane
                        $errors = 0;
                        $failure = null;

                        // Aktualizacja informacji o sukcesie w bazie danych
                        $update_query = "UPDATE domeny SET errors = '$errors', failure = NULL WHERE id = '$id'";
                        mysqli_query($polaczenie, $update_query);
                    } else {
                        $state = 'Błąd';
                        $not_working_services[] = "$host:$port"; // Dodanie do niedziałających usług
                        $errors++;
                        if (is_null($failure)) {
                            $failure = date('Y-m-d H:i:s');
                        }

                        // Zwiększenie czasu niedostępności o 10 sekund w bazie danych
                        $downtime_seconds += 10;
                        $update_query = "UPDATE domeny SET errors = '$errors', failure = '$failure', total_errors = total_errors + 1, downtime_seconds = '$downtime_seconds' WHERE id = '$id'";
                        mysqli_query($polaczenie, $update_query);
                        $alert = true; // Ustawienie flagi błędu
                    }

                    // Wyświetlanie ikony w zależności od stanu
                    if ($state === 'Ok') {
                        $icon = "<img src='icons/server-ok.svg' width='30' height='30' alt='$host'>";
                    } else {
                        if ($downtime_seconds <= 20) {
                            $icon = "<img src='icons/server-fail.svg' width='30' height='30' alt='$host'>";
                        } else {
                            $icon = "<img src='icons/server-fail.svg' width='30' height='30' class='pulse' alt='$host'>";
                        }
                    }

                    // Konwersja czasu niedostępności na HH:MM:SS
                    $downtime_formatted = secondsToTime($downtime_seconds);

                    echo "<tr>
                        <td>$icon</td>
                        <td>$id</td>
                        <td>$host</td>
                        <td>$port</td>
                        <td>$state</td>
                        <td>$errors</td>
                        <td>" . ($failure ? $failure : '-') . "</td>
                        <td class='downtime'>$downtime_formatted</td>
                    </tr>";
                }
                
                ?>
            </tbody>
        </table>

        <footer class="text-center mt-4">
            <div class="form-group">
                <label for="soundSelect">Wybierz dźwięk ostrzegawczy:</label>
                <select id="soundSelect" class="form-control" onchange="changeSound()">
                    <option value="sound/alert1.mp3">Dźwięk 1</option>
                    <option value="sound/alert2.mp3">Dźwięk 2</option>
                    <option value="sound/alert3.mp3">Dźwięk 3</option>
                </select>
            </div>
            <button id="toggleSound" class="btn btn-warning">Wyłącz dźwięk</button>
            <audio id="alertSound" src="sound/alert1.mp3" preload="auto" loop></audio>
        </footer>
        <br>
        <br>
        <!-- Animowany awatar asystenta -->
        <div id="assistant">
            <img id="assistantAvatar" src="icons/assistant.png" alt="Asystent">
            <div id="assistantSpeech" class="d-none"></div>
        </div>
    </div>

    <script>
        var alertSound = document.getElementById('alertSound');

        // Odtwarzanie dźwięku, jeśli są błędy
        if (<?php echo json_encode($alert); ?>) {
            alertSound.play().catch(function(error) {
                console.log('Błąd odtwarzania dźwięku: ', error); // Log błędu
            });
        } else {
            console.log('Wszystkie usługi działają poprawnie.'); // Log, gdy wszystko jest w porządku
        }

        // Przełączanie dźwięku
        var soundEnabled = true;
        document.getElementById('toggleSound').addEventListener('click', function () {
            soundEnabled = !soundEnabled;
            if (soundEnabled) {
                alertSound.play();
                this.textContent = 'Wyłącz dźwięk';
            } else {
                alertSound.pause();
                this.textContent = 'Włącz dźwięk';
            }
        });

        function changeSound() {
            var selectedSound = document.getElementById('soundSelect').value;
            alertSound.src = selectedSound;
            if (soundEnabled) {
                alertSound.play().catch(function(error) {
                    console.log('Błąd odtwarzania dźwięku: ', error); // Log błędu
                });
            }
        }

        // Przekazanie listy działających i niedziałających usług do JavaScript
        const workingServices = <?php echo json_encode($working_services); ?>;
        const notWorkingServices = <?php echo json_encode($not_working_services); ?>;

        // Zdarzenie do zadawania pytań asystentowi
        document.getElementById('assistantAvatar').addEventListener('click', function() {
            const question = prompt("Co chcesz wiedzieć? (np. 'Jakie usługi nie działają?')");
            if (question) {
                answerQuestion(question);
            }
        });

        function answerQuestion(question) {
            let response = '';
            if (question.includes('nie działają') || question.includes('błąd')) {
                response = 'Następujące usługi nie działają: ' + notWorkingServices.join(', ');
            } else if (question.includes('działają')) {
                response = 'Następujące usługi działają: ' + workingServices.join(', ');
            } else {
                response = 'Nie rozumiem pytania. Proszę zadaj je ponownie.';
            }
            document.getElementById('assistantSpeech').innerText = response;
            document.getElementById('assistantSpeech').classList.remove('d-none');
        }
    </script>
</body>

</html>
