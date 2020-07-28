<?php

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
        <link rel="stylesheet" type="text/css" href="Styles/StyleSheet_ad.css"/>
    </head>
    <body>
        <div id="wrapper">
            <div id="banner">
            </div>
        <nav id="navigation">
            <ul id="nav">
            <li><a href="Kunde.php">Kunde</a></li>
                <li><a href="Mitarbeiter.php">Mitarbeiter</a></li>
                <li><a href="aArtikel.php">aArtikel</a></li>
                <li><a href="aBezahlung.php">aBezahlung</a></li>
                <li><a href="aBestellungen.php">aBestellungen</a></li>
                <li><a href="Admin.php">Logout</a></li>
            </ul>      
        </nav>

<br>
<br>           
            
<div>
<b>Bestellungen durchsuchen:</b>                      
        <form id='searchform' action='aBestellungen.php' method='get'>
        <input id='search' name='search' typea='search' size='40' value='Bestellnummer'/>
        <input id='submit' type='submit' value='Suchen' />
         </form>
        <form action=aBestellungen.php method = 'get'>
            <input id='clear' name='clear' type='hidden' size='30' value='' />
            <input id='submit' type='submit' value='Alle Bestellungen anzeigen' /> 
        </form>        
        
<?php
  // check if search view of list view
  if (isset($_GET['search'])) 
  {
    $sql= "SELECT  datum,bestell_nr,artikel_nr,tracking_id,kd_nr FROM ( Taetigt NATURAL JOIN Bestellung ) WHERE bestell_nr = '" . $_GET['search'] . "'";
  } else 
  {
      $sql= "SELECT  datum,bestell_nr,artikel_nr,tracking_id,kd_nr FROM (Taetigt NATURAL JOIN Bestellung )";
  }
 
  // execute sql statement
  $stmt = oci_parse($conn, $sql);
  oci_execute($stmt);
?>

<table>
<thead>
<tr>
<th>Datum</th>
<th>Bestell_Nr</th>
  <th>Artikel_Nr</th>
     <th>Tracking_ID</th>
      <th>Kundennummer</th>


</tr>
</thead>
<tbody>                      
<?php
  // fetch rows of the executed sql query
 
  while ($row = oci_fetch_assoc($stmt)) 
  {
    echo "<tr>";
    echo "<td>" . $row['DATUM'] . "</td>";
    echo "<td>" . $row['BESTELL_NR'] . "</td>";
    echo "<td>" . $row['ARTIKEL_NR'] .  "</td>";
    echo "<td>" . $row['TRACKING_ID'] . "</td>";
    echo "<td>" . $row['KD_NR'] . "</td>";
    echo "</tr>";
  }
  
?>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Bestellungen gefunden!</div>
 <?php  oci_free_statement($stmt); ?>

</tbody>
</table>

 



<?php
 
 // clean up connections
 oci_close($conn);
?>


</body>
</html>
            
            <footer>
                <p>DBS Projekt WS18| Nemanja Srdanovic | 01576891</p>
            </footer>
        </div>
  