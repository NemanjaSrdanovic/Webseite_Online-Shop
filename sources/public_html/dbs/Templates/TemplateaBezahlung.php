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
<b>Bezahlungen durchsuchen:</b>       
        <form id='searchform' action='aBezahlung.php' method='get'>
        <input id='search' name='search' type='search' size='40' value='search'/>
        <input id='submit' type='submit' value='Suchen' />
         </form>
        <form action=aBezahlung.php method = 'get'>
            <input id='clear' name='clear' type='hidden' size='30' value='' />
            <input id='submit' type='submit' value='Alle Bezahlungen anzeigen' /> 
        </form>        
        
<?php
  // check if search view of list view
  if (isset($_GET['search'])) 
  {
    $sql= "SELECT  zahlungs_id,artikel_nr,kd_nr,zahlungsart,wert FROM ( Taetigt NATURAL JOIN Bezahlung ) WHERE zahlungs_id = '" . $_GET['search'] . "'";

  } else 
  {
    $sql = "SELECT  zahlungs_id,artikel_nr,kd_nr,zahlungsart,wert FROM ( Taetigt NATURAL JOIN Bezahlung )";
  }
 
  // execute sql statement
  $stmt = oci_parse($conn, $sql);
  oci_execute($stmt);
?>

<table>
<thead>
<tr>
 <th>Zahlungs_ID</th>
  <th>Artikelnummer</th>
   <th>Kundennummer</th>
   <th>Zahlungsart</th>
   <th>Wert</th>
</tr>
</thead>
<tbody>                      
<?php
  // fetch rows of the executed sql query
 
  while ($row = oci_fetch_assoc($stmt)) 
  {
    echo "<tr>";
    echo "<td>" . $row['ZAHLUNGS_ID'] . "</td>";
    echo "<td>" . $row['ARTIKEL_NR'] . "</td>";
    echo "<td>" . $row['KD_NR'] .  "</td>";
    echo "<td>" . $row['ZAHLUNGSART'] .  "</td>";
    echo "<td>" . $row['WERT'] .  "</td>";
    echo "</tr>";
  }
  
?>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Zahlungen gefunden!</div>
 <?php  oci_free_statement($stmt); ?>

</tbody>
</table>


<?php
 
 // clean up connection
 oci_close($conn);
?>


</body>
</html>
            
            <footer>
                <p>DBS Projekt WS18| Nemanja Srdanovic | 01576891</p>
            </footer>
        </div>
  
