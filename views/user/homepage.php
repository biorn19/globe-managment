<html>
    <head>
        <link href="src/homepage.css" rel="stylesheet" />
        <title>User</title>
    </head>

    <body>
        <h1> Hello user <?= $_SESSION['name'] ?> </h1>
        <form method="POST" action="/globe-managment/logout">
            <button type="submit">Logout</button>
        </form>
        
    </body>
</html>