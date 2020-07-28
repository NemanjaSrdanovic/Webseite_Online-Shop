
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

  <form id='insertform' action= 'Kunde.php' method='get'>
  <b>Neue Kunden einfuegen:</b>
	<table style='border: 1px solid #DDDDDD'>
	  <thead>
	    <tr>
	      <th>Username</th>
	      <th>IBAN</th>
	      </tr>
	  </thead>
	  <tbody>
	     <tr>
                <td>
	           <input id='Username' name='Username' type='text' size='25' value='<?php echo $_GET['Username']; ?>' />
                </td>
                <td>
	                <input id='IBAN' name='IBAN' type='text' size='20' value='<?php echo $_GET['IBAN']; ?>' />
                </td>
      </tr>
           </tbody>
        </table>
        <input id='submit' type='submit' value='Insert!' />
  </form>
</div>


<?php

//Handle insert
  if (isset($_GET['Username'])) 
  {
    //Prepare insert statementd
    $sql = "INSERT INTO kunde (Username, IBAN) VALUES( '"  . $_GET['Username'] . "' , '" . $_GET['IBAN'] . "' )";
    //Parse and execute statement
    $insert = oci_parse($conn, $sql);
    oci_execute($insert);
    $conn_err=oci_error($conn);
    $insert_err=oci_error($insert);
    if(!$conn_err & !$insert_err){
	print("Erfolgreich eingeführt ");
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
  <form id='insertform' action= 'Kunde.php' method='get'>
  <b>Kunden loesche:</b>
	<table style='border: 1px solid #DDDDDD'>
	  <thead>
	    <tr>
	      <th>Kundennummer</th>
	      </tr>
	  </thead>
	  <tbody>
	     <tr>
                <td>
	           <input id='Kundennummer' name='Kundennummer' type='text' size='25' value='<?php echo $_GET['Kundennummer']; ?>' />
                </td>
      </tr>
           </tbody>
        </table>
        <input id='submit' type='submit' value='Delete!' />
  </form>
</div>

<?php
  //Handle insert
  if (isset($_GET['Kundennummer'])) 
  {
    //Prepare insert statementd
    
    $sql2 = "DELETE FROM Kunde WHERE Kd_Nr = '" . $_GET['Kundennummer'] . "' ";
    //Parse and execute statement
    $insert2 = oci_parse($conn, $sql2);
    oci_execute($insert2);
    $conn_err2=oci_error($conn);
    $insert_err2=oci_error($insert2);
    if(!$conn_err2 & !$insert_err2){
	print("Erfolgreich geloescht");
 	print("<br>");
    }
    //Print potential errors and warnings
    else{
       print($conn_err2);
       print_r($insert_err2);
       print("<br>");
    }
    oci_free_statement($insert2);
  } 
?>
<br>

<div>
<b>Kunden durchsuchen:</b> 
        <form id='searchform' action='Kunde.php' method='get'>
        <input id='search' name='search' type='search' size='40' value='Username'/>
        <input id='submit' type='submit' value='Suchen' />
         </form>
        <form action=Kunde.php method = 'get'>
            <input id='clear' name='clear' type='hidden' size='30' value='' />
            <input id='submit' type='submit' value='Alle Kunden anzeigen' /> 
        </form>
            
        
<?php
  // check if search view of list view
  if (isset($_GET['search'])) 
  {
    $sql = "SELECT * FROM kunde WHERE username like '%" . $_GET['search'] . "%' ORDER BY KD_Nr";
  } else 
  {
    $sql = "SELECT * FROM kunde ORDER BY KD_Nr";
  }
 
  // execute sql statement
  $stmt = oci_parse($conn, $sql);
  oci_execute($stmt);
?>

<table>
<thead>
<tr>
 <th>Kunde_Nr</th>
  <th>Username</th>
   <th>IBAN</th>
</tr>
</thead>
<tbody>                      
<?php
  // fetch rows of the executed sql query
 
  while ($row = oci_fetch_assoc($stmt)) 
  {
    echo "<tr>";
    echo "<td>" . $row['KD_NR'] . "</td>";
    echo "<td>" . $row['USERNAME'] . "</td>";
    echo "<td>" . $row['IBAN'] .  "</td>";
    echo "</tr>";
  }
  
?>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Kunden gefunden!</div>
 <?php  oci_free_statement($stmt); ?>

</tbody>
</table>

</body>
</html>
            
            <footer>
                <p>DBS Projekt WS18| Nemanja Srdanovic | 01576891</p>
            </footer>
        </div>
  


 
 


 



