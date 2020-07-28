<?php

//require 'Credentials.php';

$user = 'a01576891';
$pass = 'dbs18';
$database = 'lab';


// establish database connection
$conn = oci_connect($user, $pass, $database);
if (!$conn) exit;

?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html"; charset="UTF-8">
        <title><?php echo $title; ?></title>>
        <link rel="stylesheet" type="text/css" href="Styles/StyleSheet_a.css"/>
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

        <br>
        <br>           
 <div>
 <b>Artikel durchsuchen:</b> 
        <form id='searchform' action='Artikel.php' method='get'>
        <input id='search' name='search' type='search' size='40' value='Bezeichnung'/>
        <input id='submit' type='submit' value='Suchen' />
         </form>
        <form action=Artikel.php method = 'get'>
            <input id='clear' name='clear' type='hidden' size='30' value='' />
            <input id='submit' type='submit' value='Alle Artikel' /> 
        </form>
            
        
<?php
  // check if search view of list view
  if (isset($_GET['search'])) 
  {
    $sql = "SELECT * FROM artikel WHERE bezeichnung like '%" . $_GET['search'] . "%' ORDER BY ARTIKEL_NR";
  } else 
  {
    $sql = "SELECT * FROM artikel ORDER BY ARTIKEL_NR";
  }
 
  // execute sql statement
  $stmt = oci_parse($conn, $sql);
  oci_execute($stmt);
?>

<table>
<thead>
<tr>
 <th>Artikel_Nr</th>
  <th>Preis</th>
   <th>Type</th>
    <th>Bezeichnung</th>
</tr>
</thead>
<tbody>                      
<?php
  // fetch rows of the executed sql query
 
  while ($row = oci_fetch_assoc($stmt)) 
  {
    echo "<tr>";
    echo "<td>" . $row['ARTIKEL_NR'] . "</td>";
    echo "<td>" . $row['PREIS'] . "</td>";
    echo "<td>" . $row['TYPE'] .  "</td>";
    echo "<td>" . $row['BEZEICHNUNG'] . "</td>";
    echo "</tr>";
  }
  
?>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Artikel gefunden!</div>
 <?php  oci_free_statement($stmt); ?>

</tbody>
</table>


<?php
 
 // clean up connections
 oci_free_statement($sproc);
 oci_close($conn);
?>


</body>
</html>
            
            <footer>
                <p>DBS Projekt WS18| Nemanja Srdanovic | 01576891</p>
            </footer>
        </div>
  


 
 


 



