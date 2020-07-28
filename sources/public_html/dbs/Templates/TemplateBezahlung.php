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
        <link rel="stylesheet" type="text/css" href="Styles/StyleSheet_bz.css"/>
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
  <form id='insertform' action= 'Bezahlung.php' method='get'>
  <b> Zahlung zur getaetigten Bestellung durchfueren: </b>
	<table style='border: 1px solid #DDDDDD'>
	  <thead>
	    <tr>
	      <th>Zahlungsart</th>
          <th>Kunden_Nr</th>
          <th>Artikel_Nr</th>  
       </tr>
	  </thead>
	  <tbody>
	     <tr>
	        <td>
	           <input id='Zahlungsart' name='Zahlungsart' type='text' size='20' value='<?php echo $_GET['Zahlungsart']; ?>' />
            </td>
            <td>
	           <input id='KD_Nr' name='KD_Nr' type='number' size='10' value='<?php echo $_GET['KD_Nr']; ?>' />
             </td>
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
  if (isset($_GET['Zahlungsart'])) 
  {
    //Prepare insert statementd
    
    $sql = "INSERT INTO bezahlung (Zahlungsart) VALUES ( '" . $_GET['Zahlungsart'] . "' )";
    $sql2 = "INSERT INTO taetigt (Artikel_NR,KD_Nr) VALUES ( '" . $_GET['Artikel_Nr'] . "' ,'"  . $_GET['KD_Nr'] . "' )";
    //Parse and execute statement
    $insert = oci_parse($conn, $sql);
    oci_execute($insert);
    $conn_err=oci_error($conn);
    $insert_err=oci_error($insert);

    //Parse and execute statement sql2
    $insert2 = oci_parse($conn, $sql2);
    oci_execute($insert2);
    $conn_err2=oci_error($conn);
    $insert_err2=oci_error($insert2);
    
    if(!$conn_err & !$conn_err2 & !$insert_err & !$insert_err2){
	print("Zahlung durchgefuert");
 	print("<br>");
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
 <b> Zahlungen zur angegebenen Kundennummer durchsuchen: </b>    
        <form id='searchform' action='Bezahlung.php' method='get'>
        <input id='search' name='search' type='search' size='40' value='Kunden_Nr'/>
        <input id='submit' type='submit' value='Alle Bezahlungen zur KD_NR anzeigen' />
         </form>
        <form action=Bezahlung.php method = 'get'>
            <input id='clear' name='clear' type='hidden' size='30' value='' />
            <input id='submit' type='submit' value='clear' /> 
        </form>        
        
<?php
  // check if search view of list view
  if (isset($_GET['search'])) 
  {
    $sql = "SELECT * FROM Taetigt WHERE KD_NR = '" . $_GET['search'] . "'  ORDER BY ZAHLUNGS_ID";
  } 
  // execute sql statement
  $stmt = oci_parse($conn, $sql);
  oci_execute($stmt);
?>

<table>
<thead>
<tr>
 <th>Artikel_Nr</th>
  <th>Bestell_Nr</th>
   <th>Zahlungs_ID</th>
</tr>
</thead>
<tbody>                      
<?php
  // fetch rows of the executed sql query
 
  while ($row = oci_fetch_assoc($stmt)) 
  {
    echo "<tr>";
    echo "<td>" . $row['ARTIKEL_NR'] . "</td>";
    echo "<td>" . $row['BESTELL_NR'] . "</td>";
    echo "<td>" . $row['ZAHLUNGS_ID'] .  "</td>";
    echo "</tr>";
  }
  
?>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Zahlungen gefunden!</div>
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
  






 
 


 



