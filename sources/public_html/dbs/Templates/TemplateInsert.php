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
        <link rel="stylesheet" type="text/css" href="Styles/StyleSheet.css"/>
    </head>
    <body>
        <div id="wrapper">
            <div id="banner">
            </div>
        <nav id="navigation">
            <ul id="nav">
            <li><a href="Kunde.php">Kunde</a></li>
                <li><a href="Mitarbeiter.php">Mitarbeiter</a></li>
                <li><a href="aArtikel.php">Artikel</a></li>
                <li><a href="aBezahlung.php">Bezahlung</a></li>
                <li><a href="aBezahlung.php">Bestellungen</a></li>
                <li><a href="Admin.php">Logout</a></li>
            </ul>      
        </nav>

            <div id="content_area">
                <?php echo $content; ?>
            </div>

            <div id="sidebar">
                
            </div>
            
            <div>
      
               
        <form id='searchform' action='Artikel.php' method='get'>
        <input id='search' name='search' type='search' size='40' value='search'/>
        <input id='submit' type='submit' value='Show all Artikels' />
         </form>
        <form action=Artikel.php method = 'get'>
            <input id='clear' name='clear' type='hidden' size='30' value='' />
            <input id='submit' type='submit' value='clear' /> 
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
     <th>Steuernummer</th>
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
    echo "<td>" . $row['STEUERNUMMER'] . "</td>";
    echo "</tr>";
  }
  
?>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Artikel gefunden!</div>
 <?php  oci_free_statement($stmt); ?>

</tbody>
</table>

 
<div>
  <form id='insertform' action= 'Artikel.php' method='get'>
    Neue Artikel einfuegen:
	<table style='border: 1px solid #DDDDDD'>
	  <thead>
	    <tr>
	      <th>Artikel_Nr</th>
	      <th>Preis</th>
	      <th>Type</th>
	      <th>Bezeichnung</th>
	      <th>Steuernummer</th>
       </tr>
	  </thead>
	  <tbody>
	     <tr>
	        <td>
	           <input id='Artikel_Nr' name='Artikel_Nr' type='number' size='10' value='<?php echo $_GET['Artikel_Nr']; ?>' />
                </td>
                <td>
	           <input id='Preis' name='Preis' type='number' size='10' value='<?php echo $_GET['Preis']; ?>' />
                </td>
		<td>
		   <input id='Type' name='Type' type='text' size='20' value='<?php echo $_GET['Type']; ?>' />
		</td>
		<td>
		   <input id='Bezeichnung' name='Bezeichnung' type='text' size='20' value='<?php echo $_GET['Bezeichnung']; ?>' />
		</td>
      <td>
	      <input id='Steuernummer' name='Steuernummer' type='number' size='15' value='<?php echo $_GET['Steuernummer']; ?>' />
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
    $sql = "INSERT INTO artikel VALUES(" . $_GET['Artikel_Nr'] . ",'"  . $_GET['Preis'] . "','" . $_GET['Type'] . "','" . $_GET['Bezeichnung'] . "','". $_GET['Steuernummer'] . "')";
    //Parse and execute statement
    $insert = oci_parse($conn, $sql);
    oci_execute($insert);
    $conn_err=oci_error($conn);
    $insert_err=oci_error($insert);
    if(!$conn_err & !$insert_err){
	print("Successfully inserted");
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


<div>
 <form id='searchmid' action='Artikel.php' method='get'>
   Suche Mitarbeiter_Id zu bestimmten Artikel (Bezeichnung):
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
    $sproc = oci_parse($conn, 'begin art_mid (:p1, :p2); end;');
    //Bind variables, p1=input (nachname), p2=output (abtnr)
    oci_bind_by_name($sproc, ':p1', $amid);
    oci_bind_by_name($sproc, ':p2', $mitid, 20);
    oci_execute($sproc);
    $conn_err=oci_error($conn);
    $proc_err=oci_error($sproc);
    //If there have been no Connection or Database errors, print department
    if(!$conn_err && !$proc_err){
       echo("<br><b>" . $amid. " bearbeitet Mitarbeiter mit der ID: " . $mitid . "</b><br>" );  // prints OUT parameter of stored procedure
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
 oci_close($conn);
?>


</body>
</html>
            
            <footer>
                <p>DBS Projekt WS18| Nemanja Srdanovic | 01576891</p>
            </footer>
        </div>
  


 
 


 



