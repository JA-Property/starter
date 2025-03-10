<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?? 'JA Property' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>JA Property Framework</h1>
        </div>
    </header>
    
    <div class="container">
        <?php include $content; ?>
    </div>
    
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> JA Property Framework</p>
        </div>
    </footer>
</body>
</html>