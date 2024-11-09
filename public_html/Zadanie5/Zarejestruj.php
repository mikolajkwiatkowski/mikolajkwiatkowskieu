    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kwiatkowski Mikołaj - Rejestracja</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <main>
            <div class="row g-5">
                <div class="col-md-7 col-lg-8">
                    <h4 class="mb-3 mt-5">Zarejestruj się</h4>
                    <form class="needs-validation" method="post" action="dodaj.php" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="username" class="form-label">Login</label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control" name="login" id="login" placeholder="Login" required>
                                    <div class="invalid-feedback" id="loginFeedback">
                                        Login może zawierać tylko litery i cyfry.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Hasło</label>
                                <div class="input-group has-validation">
                                    <input type="password" class="form-control" name="password1" placeholder="Hasło" required>
                                    <input type="password" class="form-control" name="password2" placeholder="Powtórz hasło" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="avatar" class="form-label">Avatar</label>
                                <div class="input-group has-validation">
                                    <input type="file" class="form-control" name="avatar" placeholder="Avatar">
                                </div>
                            </div>
                        </div>
                        <button class="w-100 btn btn-primary btn-lg" type="submit">Zarejestruj</button>
                        <p class="mt-5 mb-3 text-body-secondary">Masz już konto? <a href="Zaloguj.php">Zaloguj się</a></p>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function validateForm() {
            const loginField = document.getElementById('login');
            const loginFeedback = document.getElementById('loginFeedback');
            const loginValue = loginField.value;

            // Regularne wyrażenie dla liter i cyfr
            const regex = /^[a-zA-Z0-9]+$/;

            if (!regex.test(loginValue)) {
                loginField.classList.add('is-invalid'); // Dodaje styl dla niepoprawnego pola
                loginFeedback.style.display = 'block';  // Pokazuje komunikat o błędzie
                return false; // Blokuje wysłanie formularza
            } else {
                loginField.classList.remove('is-invalid');
                loginFeedback.style.display = 'none';
                return true; // Pozwala wysłać formularz
            }
        }
    </script>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
