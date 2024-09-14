<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        body.unique-login-background {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #121212;
            background-image: url('./neon.jpg');
            background-size: cover;
            background-position: center;
        }

        .unique-background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            filter: blur(0px);
            z-index: -1;
            background-image: inherit;
        }

        .unique-login-container {
            background-color: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(10px) !important;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: left; 
            max-width: 400px; /* Adjust the width here */
            width: 100%;
            color: #fff;
        }

        h2.unique-title {
            margin-bottom: 20px;
        }

        .unique-form-group {
            margin-bottom: 15px;
        }

        label.unique-label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
        }

        input.unique-input[type="email"],
        input.unique-input[type="password"] {
            width: calc(100% - 0px);
            padding: 10px;
            box-sizing: border-box;
            border: none;
            background-color: rgba(255, 255, 255, 0.3) !important;
            border-radius: 5px;
            margin-bottom: 10px;
            color: #fff !important; /* Text color in input fields */
        }

        button.unique-button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button.unique-button:hover {
            background-color: #0056b3;
        }

        .unique-alert {
            color: #ff0000;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 0.75rem 1.25rem;
            border-radius: 0.25rem;
            margin-top: 10px;
        }
    </style>
</head>
<body class="unique-login-background">
    <div class="unique-background-image"></div>
    <div class="unique-login-container">
        <h2 class="unique-title">
            <?php 
                session_start();
                include('./db_connect.php');

                // Check if session is initialized
                if (isset($_SESSION['system']['name'])) {
                    echo $_SESSION['system']['name'] . ' - Admin';
                } else {
                    echo 'Login';
                }
            ?>
        </h2>
        <form id="login-form">
            <div class="unique-form-group">
                <label for="email" class="unique-label">Email</label>
                <input type="email" id="email" name="email" required class="unique-input">
            </div>
            <div class="unique-form-group">
                <label for="password" class="unique-label">Password</label>
                <input type="password" id="password" name="password" required class="unique-input">
            </div>
            <button type="submit" class="unique-button">Sign In</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#login-form').submit(function(e){
                e.preventDefault();
                if($(this).find('.unique-alert').length > 0 )
                    $(this).find('.unique-alert').remove();
                $.ajax({
                    url: 'ajax.php?action=login',
                    method: 'POST',
                    data: $(this).serialize(),
                    error: function(err) {
                        console.log(err);
                    },
                    success: function(resp) {
                        if(resp == 1){
                            location.href = 'index.php?page=home';
                        } else {
                            $('#login-form').prepend('<div class="unique-alert">Username or password is incorrect.</div>');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
