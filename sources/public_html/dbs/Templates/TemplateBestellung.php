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
        <link rel="stylesheet" type="text/css" href="Styles/StyleSheet_b.css"/>
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
  <form id='insertform' action= 'Bestellung.php' method='get'>
    <b>Neue Bestellung taetigen:</b>
	<table style='border: 1px solid #DDDDDD'>
	  <thead>
	    <tr>
	      <th>Artikel_Nr</th>
       </tr>
	  </thead>
	  <tbody>
	     <tr>
	            <td>
	           <input id='Artikel_Nr' name='Artikel_Nr' type='number' size='10' value='<?php echo $_GET['Artikel_Nr']; ?>' />
                </td>
	      </tr>
           </tbody>
        </table>
        <input id='submit' type='submit' value='Insert!' />
  </form>
</div>


<?php


//Handle insert
  if (isset($_GET['Artikel_Nr'])) 
  {
    //Prepare insert statementd
    
    $val = rand( 1, 200 );
    print($val);

    $sql = "INSERT INTO Bestellung (Artikel_NR) VALUES ( '" . $_GET['Artikel_Nr'] . "' )";
    $sql2= "INSERT INTO Bearbeitet(Artikel_NR, Mitarbeiter_ID) VALUES ( '" . $_GET['Artikel_Nr'] . "','$val' )";
    //Parse and execute sql statement 
    $insert = oci_parse($conn, $sql);
    oci_execute($insert);
    $conn_err=oci_error($conn);
    $insert_err=oci_error($insert);
  
    //Parse and execute sql2 statement 
    $insert2 = oci_parse($conn, $sql2);
    oci_execute($insert2);
    $conn_err2=oci_error($conn);
    $insert_err2=oci_error($insert2);
  

    if(!$conn_err & !$conn_err2 &  !$insert_err & !$insert_err2){
    print("Bestellung durchgefuert");
     print("<br>");
     header("Location: Bezahlung.php");
    }
    //Print potential errors and warnings
    else{
        print($conn_err);
        print($conn_err2);
        print_r($insert_err);
        print_r($insert_err2);
        print("<br>");
    }
    oci_free_statement($insert);
    oci_free_statement($insert2);
} 

?>

<br>
<div>
<b>Bestellungen zur Kundennummer durchsuchen:</b>             
        <form id='searchform' action='Bestellung.php' method='get'>
        <input id='search' name='search' type='search' size='40' value='Kunden_Nr'/>
        <input id='submit' type='submit' value='Alle Bestellungen zur KD_NR anzeigen' />
         </form>
        <form action=Bestellung.php method = 'get'>
            <input id='clear' name='clear' type='hidden' size='30' value='' />
            <input id='submit' type='submit' value='clear' /> 
        </form>        
        
<?php
  // check if search view of list view
  if (isset($_GET['search'])) 
  {
    $sql="SELECT  datum,artikel_nr,bestell_nr,tracking_id  FROM (Taetigt NATURAL JOIN Bestellung ) WHERE KD_NR = '" . $_GET['search'] . "'";
  } 
 
  // execute sql statement
  $stmt = oci_parse($conn, $sql);
  oci_execute($stmt);
?>

<table>
<thead>
<tr>
 <th>Datum</th>
  <th>Artikel_Nr</th>
   <th>Bestell_Nr</th>
    <th>Tracking_ID</th>
</tr>
</thead>
<tbody>                      
<?php
  // fetch rows of the executed sql query
 
  while ($row = oci_fetch_assoc($stmt)) 
  {
    echo "<tr>";
    echo "<td>" . $row['DATUM'] . "</td>";
    echo "<td>" . $row['ARTIKEL_NR'] . "</td>";
    echo "<td>" . $row['BESTELL_NR'] .  "</td>";
    echo "<td>" . $row['TRACKING_ID'] . "</td>";
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
  






 
 


 



