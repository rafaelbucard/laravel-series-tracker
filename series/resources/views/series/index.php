<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Series</title>
</head>
<body>
    <ul>
        <?php foreach($series as $serie):?>
            <li><?= $serie; ?></li>
        <?php endforeach; ?>
    </ul>
    
</body>
</html>