<html>
    <head>
    <link href="src/register.css" rel="stylesheet" />
        <title>Globe</title>
    </head>

    <body>
        <h1>Register</h1>

        <?php if (isset($_SESSION["errors"])): ?>
        <?php 
        foreach ($_SESSION["errors"] as $error) {
            echo "<p>$error</p>";
        }
        unset($_SESSION["errors"]);
        ?>
        <?php endif; ?>

        <form method="POST" action="/globe-managment/register/submit">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your Name"/>
            <label for="surname">Surname</label>
            <input type="text" id="surname" name="surname" placeholder="Enter your Surname"/>
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" id="date_of_birth" name="date_of_birth"/>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email"/>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password"/>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password"/>
            <button type="submit">Register</button>
        </form>
    </body>
</html>