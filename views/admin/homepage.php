<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<html>
    <head>
    <link href="../src/admin.css" rel="stylesheet" />
        <title>Admin</title>
    </head>

    <body>
        <h1> Hello user <?= $_SESSION['name'] ?> </h1>
        <form method="POST" action="/globe-managment/logout">
            <button type="submit">Logout</button>
        </form>
        <?php foreach($users as $user): ?>
            <form method="POST" action="/globe-managment/admin/calculate/user/payment">
                <label>User: <?= $user['name'] .' '. $user['surname'] ?> </label>
                <input type="hidden" name="id" value="<?= $user['id'] ?>" />
                <button type="submit">Calculate Wage</button>
            </form>  
        <?php endforeach ?>

        <?php if (isset($_SESSION['new_wage'])): ?>
            <?php $newWage = $_SESSION['new_wage']; ?>
            <div>
                User: <?= htmlspecialchars($newWage['user']) ?> wage was created with amount: <?= htmlspecialchars($newWage['wage']) ?>
            </div>
            <?php unset($_SESSION['new_wage']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['no_wage'])): ?>
            <?php $noWage = $_SESSION['no_wage']; ?>
            <div>
                User: <?= htmlspecialchars($noWage['user']) . ' ' . htmlspecialchars($noWage['wage']) ?>
            </div>
            <?php unset($_SESSION['no_wage']); ?>
        <?php endif; ?>
        
    </body>
</html>