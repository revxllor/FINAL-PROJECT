<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Task Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-body">
    <form id="loginForm" class="shadow p-4">
        <h3 class="display-4">LOGIN</h3>

        <div id="errorMessage" class="alert alert-danger <?php echo isset($_GET['error']) ? '' : 'd-none'; ?>" role="alert">
            <?php echo isset($_GET['error']) ? stripcslashes($_GET['error']) : ''; ?>
        </div>

        <div id="successMessage" class="alert alert-success <?php echo isset($_GET['success']) ? '' : 'd-none'; ?>" role="alert">
            <?php echo isset($_GET['success']) ? stripcslashes($_GET['success']) : ''; ?>
        </div>

        <div class="mb-3">
            <label for="user_name" class="form-label">User name</label>
            <input type="text" class="form-control" name="user_name" id="user_name" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>

        <div class="mt-3">
            <a href="register.php">Create an account</a>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();

                var user_name = $('#user_name').val();
                var password = $('#password').val();

                $('#errorMessage').addClass('d-none');
                $('#successMessage').addClass('d-none');

                $.ajax({
                    type: 'POST',
                    url: 'app/login.php',
                    data: { user_name: user_name, password: password },
                    success: function (response) {
                        var data = JSON.parse(response);

                        if (data.error) {
                            $('#errorMessage').removeClass('d-none').text(data.error);
                        } else if (data.success) {
                            $('#successMessage').removeClass('d-none').text(data.success);
                            window.location.href = 'index.php';
                        }
                    },
                    error: function () {
                        $('#errorMessage').removeClass('d-none').text("An unknown error occurred.");
                    }
                });
            });
        });
    </script>
</body>
</html>
