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
  <form id='insertform' action= 'Mitarbeiter.php' method='get'>
 <b>  Neuen Mitarbeiter einfuegen: </b>
	<table style='border: 1px solid #DDDDDD'>
	  <thead>
	    <tr>
	      <th>Name</th>
	      </tr>
	  </thead>
	  <tbody>
	     <tr>
                  <td>
		            <input id='Nachname' name='Nachname' type='text' size='20' value='<?php echo $_GET['Nachname']; ?>' />
		            </td>
	      </tr>
           </tbody>
        </table>
        <input id='submit' type='submit' value='Insert!' />
  </form>
</div>


<?php
  //Handle insert
  if (isset($_GET['Nachname'])) 
  {
    //Prepare insert statementd
    $sql = "INSERT INTO Support_MA  (Nachname) VALUES( '" . $_GET['Nachname'] . "' )";
    //Parse and execute statement
    $insert = oci_parse($conn, $sql);
    oci_execute($insert);
    $conn_err=oci_error($conn);
    $insert_err=oci_error($insert);
    if(!$conn_err & !$insert_err){
	print("Erfolgreich eingefuegt");
 	print("<br>");
    }
    //Print potential errors and warnings
    else{
       print($conn_err);
       print_r($insert_err);
       print("<br>");
    }
    oci_free_statement($insert);
  } 
?>
<br>

<div>
 <form id='searchmid' action='Mitarbeiter.php' method='get'>
   <b>Suche mit der Bestellnummer den Mitarbeiter, der die Bestellung bearbeitet:</b>
     <input id='amid' name='amid' type='text' size='20' value='<?php echo $_GET['amid']; ?>' />
     <input id='submit' type='submit' value='Aufruf Stored Procedure!' />
 </form>
</div>


<?php
 //Handle Stored Procedure
 if (isset($_GET['amid']))
 {
    //Call Stored Procedure  
    $amid = $_GET['amid'];
    $mitid='';
    $sproc = oci_parse($conn, 'begin bestell_mid (:p1, :p2); end;');
    //Bind variables, p1=input (nachname), p2=output (abtnr)
    oci_bind_by_name($sproc, ':p1', $amid);
    oci_bind_by_name($sproc, ':p2', $mitid, 20);
    oci_execute($sproc);
    $conn_err=oci_error($conn);
    $proc_err=oci_error($sproc);
    //If there have been no Connection or Database errors, print department
    if(!$conn_err && !$proc_err){
       echo("Bestellnummer " . $amid. " bearbeitet Mitarbeiter mit der ID: " . $mitid . "</b><br>" );  // prints OUT parameter of stored procedure
    }
  
    else{
      //Print potential errors and warnings
     
     echo("<br><div style=\"color:red;\"><b>Bezeichnung falsch!</b></div><br>");
      // print($conn_err);
      //print_r($proc_err);
    }  
 }


 
 // clean up connections
 oci_free_statement($sproc);
?>
<br>

<div>
  <form id='detailsform' action= 'Mitarbeiter.php' method='get'>
 <b>  Details zum Mitarbeiter: </b>
	<table style='border: 1px solid #DDDDDD'>
	  <thead>
	    <tr>
	      <th>Mitarbeiter_ID</th>
	      </tr>
	  </thead>
	  <tbody>
	     <tr>
                  <td>
		            <input id='Mitarbeiter_ID' name='Mitarbeiter_ID' type='text' size='20' value='<?php echo $_GET['Mitarbeiter_ID']; ?>' />
		            </td>
	      </tr>
           </tbody>
        </table>
        <input id='submit' type='submit' value='Suchen!' />
  </form>
</div>



<?php
// check if search view of list view
if ( isset($_GET['Mitarbeiter_ID'])) 
{
 $val=$_GET['Mitarbeiter_ID'];

 if($val<201 )
{
  $sql = "SELECT  Nachname,Mitarbeiter_ID,Inbound_ID,SOFT_SKILLS 
            FROM ( Front_Office_MA NATURAL JOIN Support_MA ) 
              WHERE Mitarbeiter_ID = '" . $_GET['Mitarbeiter_ID'] . "'";
}else
 {
  $sql="SELECT Nachname, Mitarbeiter_ID,Techical_Skills,EMail 
          FROM ( Back_Office_MA NATURAL JOIN Support_MA ) 
            WHERE Mitarbeiter_ID = '" . $_GET['Mitarbeiter_ID'] . "'";
 }
} 

// execute sql statement
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<table>
<thead>
<tr>
<th>Name</th>
<th>Mitarbeiter_ID</th>
 <th>Inbound_ID</th>
 <th>E-Mail</th>
 <th>Soft Skills</th>
 <th>Techical Skills</th>
</tr>
</thead>
<tbody>                      
<?php
// fetch rows of the executed sql query

while ($row = oci_fetch_assoc($stmt)) 
{
  echo "<tr>";
  echo "<td>" . $row['NACHNAME'] . "</td>";
  echo "<td>" . $row['MITARBEITER_ID'] . "</td>";
  echo "<td>" . $row['INBOUND_ID'] .  "</td>";
  echo "<td>" . $row['EMAIL'] .  "</td>";
  echo "<td>" . $row['SOFT_SKILLS'] .  "</td>";
  echo "<td>" . $row['TECHICAL_SKILLS'] .  "</td>";
  echo "</tr>";
}

?>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Mitarbeiter gefunden!</div>
<?php  oci_free_statement($stmt); ?>

</tbody>
</table>        


<br>
<br>

<div>
<b>Mitarbeiter durchsuchen:</b>              
      <form id='searchform' action='Mitarbeiter.php' method='get'>
      <input id='search' name='search' type='search' size='40' value='Name'/>
      <input id='submit' type='submit' value='Suchen' />
       </form>
      <form action=Mitarbeiter.php method = 'get'>
          <input id='clear' name='clear' type='hidden' size='30' value='' />
          <input id='submit' type='submit' value='Alle Mitarbeiter anzeigen' /> 
      </form>
          
      
<?php
// check if search view of list view
if (isset($_GET['search'])) 
{
  $sql = "SELECT * FROM Support_MA WHERE Nachname like '%" . $_GET['search'] . "%' ORDER BY Mitarbeiter_ID ";
} else 
{
  $sql = "SELECT *  FROM Support_MA ORDER BY Mitarbeiter_ID";
}

// execute sql statement
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<table>
<thead>
<tr>
<th>Mitarbeiter_ID</th>
 <th>Name</th>
</tr>
</thead>
<tbody>                      
<?php
// fetch rows of the executed sql query

while ($row = oci_fetch_assoc($stmt)) 
{
  echo "<tr>";
  echo "<td>" . $row['MITARBEITER_ID'] . "</td>";
  echo "<td>" . $row['NACHNAME'] .  "</td>";
  echo "</tr>";
}

?>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Mitarbeiter gefunden!</div>
<?php  oci_free_statement($stmt); ?>

</tbody>
</table>          

<?php  oci_close($conn); ?>

</body>
</html>
            
            <footer>
                <p>DBS Projekt WS18| Nemanja Srdanovic | 01576891</p>
            </footer>
        </div>
  


 