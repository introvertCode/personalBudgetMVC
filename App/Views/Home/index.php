<!DOCTYPE html>
<!-- Już niepotrzebne!!!! -->
<html>
    <head>
        <meta charset = "UTF-8">
        <title>Home</title>
    </head>
    <body>
        <h1>Welcome</h1>
        <p>Hello <?php echo htmlspecialchars($name); ?>!</p>
        <!-- <p>Hello from the view!</p> -->
        <ul>
        
            <?php foreach ($colours as $colour): ?>
                <li><?php echo htmlspecialchars($colour);?></li>
            <!-- endforeach- It's mainly so you can make start and end statements clearer when creating HTML in loops -->
            <?php endforeach; ?>
        </ul>
    </body>
</html>