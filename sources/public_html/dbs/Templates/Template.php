<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html"; charset="UTF-8">
        <title><?php echo $title; ?></title>>
        <link rel="stylesheet" type="text/css" href="Styles/StyleSheet.css"/>
    </head>
    <body>
        <div id="wrapper">
            <div id="banner">
            </div>
        <nav id="navigation">
            <ul id="nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="Artikel.php">Artikel</a></li>
                <li><a href="Bestellung.php">Bestellung</a></li>
                <li><a href="Bezahlung.php">Bezahlung</a></li>
                <li><a href="Admin.php">Admin</a></li>
               
            </ul>      
        </nav>

            <div id="content_area">
                <?php echo $content; ?>
            </div>
         
            <footer>
                <p>DBS Projekt WS18| Nemanja Srdanovic | 01576891</p>
            </footer>
        </div>>
    </body>
</html>
