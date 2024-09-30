<html>
    <head>
        <link href="src/login.css" rel="stylesheet" />
        <title>Globe</title>
    </head>

    <body>
        <h1>Login</h1>

    <?php
    if (isset($_SESSION['login_error'])) : ?>
    <p>Invalid email or password</p>
    <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>
    

        <form method="POST" action="/globe-managment/login/submit">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email"/>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password"/>
            <button type="submit">Login</button>
        </form>
    </body>
</html>