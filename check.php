<?php
require_once "car.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vehicle Checker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Vehicle Checker</h1>
</header>
<main>
    <?php
    try {
        $car = new Car();
        ?>
        <table>
            <tr>
                <th>Make</th>
                <td><?= $car->getMake() ?></td>
            </tr>
            <tr>
                <th>Model</th>
                <td><?= $car->getModel() ?></td>
            </tr>
            <tr>
                <th>Colour</th>
                <td><?= $car->getColour() ?></td>
            </tr>
            <tr>
                <th>MOT Expiry Date</th>
                <td><?= $car->getExpiryDate() ?></td>
            </tr>
            <tr>
                <th>Failed MOTs</th>
                <td><?= $car->getFailedMots() ?></td>
            </tr>
        </table>
        <?php
    } catch (Exception $e) {
        echo "<p class='error'>{$e->getMessage()}</p>";
    }
    ?>
</main>
<footer>
    <p>Author: Elliot Dickerson</p>
</footer>
</body>
</html>