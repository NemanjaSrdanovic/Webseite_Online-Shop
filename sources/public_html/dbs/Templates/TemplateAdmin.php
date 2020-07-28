<?php

$user = 'a01576891';
$pass = 'dbs18';
$database = 'lab';

// establish database connection
$conn = oci_connect($user, $pass, $database);
if (!$conn) exit;

if(isset($_POST['username'])){
  $uname=$_POST['username'];
  $pass=$_POST['password'];

  $sql="SELECT * FROM LOGINFORM WHERE USERR = '".$uname. "' AND PASS = '".$pass."'limit 1 ";
  
  $stmt = oci_parse($conn, $sql);
  oci_execute($stmt);

  
  if(oci_num_row($stmt)==1)
  {
    echo "You have successfully logged in";
    exit;
  }
  else
  {
    echo "Wrong password";
    exit;
  }

 // oci_free_statement($stmt);
}
?>



<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html"; charset="UTF-8">
        <title><?php echo $title; ?></title>>
        <link rel="stylesheet" type="text/css" href="Styles/StyleSheet_bz.css"/>
        <link rel="stylesheet" type="text/css" href="Styles/StyleSheet_lg.css"/>
        <link rel="stylesheet" type="text/css" href="Styles/css/fontawesome.min.css">
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

            <div id="sidebar">
                
            </div>



    <div>
      
            <div class="container">
	<img src="Images/login.png"/>
		<form method= "POST" action= "Kunde.php">
			<div class="form-input"> 
				<input type="text" name="text" placeholder="Enter your username"/>	
			</div>
			<div class="form-input">
				<input type="password" name="password" placeholder="Enter your password"/>
			</div>
			<input type="submit" type="submit" value="LOGIN" class="btn-login"/>
		</form>
	</div>   
              
        

</body>
</html>
            
            <footer>
                <p>DBS Projekt WS18| Nemanja Srdanovic | 01576891</p>
            </footer>
        </div>
  






 
 


 



