import java.sql.*;
import oracle.jdbc.driver.*;
import java.util.Random;

public class TestDataGenerator {

public static void main(String args[]) {

try {
      Class.forName("oracle.jdbc.driver.OracleDriver");
      String database = "jdbc:oracle:thin:@131.130.122.4:1521:lab";
      String user = "a01576891";
      String pass = "dbs18";

// establish connection to database 
Connection con = DriverManager.getConnection(database, user, pass);
Statement stmt = con.createStatement();

//Data Artikel
String[] Type={"Handy" , "Laptop" , "Tablet", "Notebook","Software","Navigation","Audio & HiFi","TV","Kamera","Zubehoer"};
String[] Bezeichnung={"Samsung" , "Huawei" , "Apple" , "Lenovo", "Microsoft", "Acer", "HP", "Sony", "LG", "Asus" };
int ArtNr=0; 	 
   
//Data Kunde
String[] Nachnamen={"Wagner" , "Gruber" , "Winkler", "Weber","Huber","Bauer","Wimmer","Schmid","Wallner","Wolf", "Steiner","Pichler", 
"Moser", "Mayer","Hofer","Leitner","Berger","Fuchs","Eder","Fischer" };

String[] IB={"DE" , "AT" , "NL", "ES", "FR"};
 
String[] Vornamen={"Anna" , "Emma" , "Maximilian", "Paul", "David", "Jakob", "Sophia", "Valentina","Hannah","Simon"};

// Data Bezahlung
String[] Zahlungsart={"Lastschrift","Rechnung","Nachnahme","PayPal","Vorkasse"};

//PK Tabelle
try
{
	String insertSql="INSERT INTO online_shop(Name, Domain) VALUES ('Amazon','www.amazon.com')";
	stmt.executeUpdate(insertSql);
}catch(Exception e)
 {
	System.err.println("Error(Online_Shop):"+e.getMessage());
 }

//Login Tabelle
try
{
	String insertSql= "INSERT INTO Loginform(Userr,Pass) VALUES('admin','admin')";
	stmt.executeUpdate(insertSql);
}catch(Exception e)
 {
	System.err.println("Error(Login):"+e.getMessage());

 }

// Artikel 10000	 
try
{
	 int count=0;
	 ResultSet rs = stmt.executeQuery("SELECT COUNT(*) FROM artikel");
      if (rs.next()) {
        count = rs.getInt(1); }
         if(count>0){ArtNr = count+1;}
         	else{ArtNr=1;}
	 	
	 	String insertSql="";
	 	for(int i=0; i<10; i++)
		{
	 	  for(int a=0; a<Type.length; a++)
	 	  {
	 	   for(int b=0; b<Bezeichnung.length; b++)
	 	   {
	 			Random random = new Random();
	    	int Preis = (int)(Math.random()*700)+500; 
 	 			
		    insertSql = "INSERT INTO artikel (artikel_nr,preis,type, bezeichnung) VALUES ('" +ArtNr+"', '" +Preis+"', '"+Type[a]+"', '"+Bezeichnung[b]+"')";
    	  stmt.executeUpdate(insertSql);
	 	    ++ArtNr;
	      }
	      
		  }
		
		}
}catch(Exception e)
	{
	 	System.err.println("Error(Artikel):"+e.getMessage());
  }
  
//Kunde  1000
try
{
  String insertSql="";
	 	for(int i=0; i<10; i++)
		{
	 	  for(int a=0; a<Nachnamen.length; a++)
	 	  {
	 	   for(int b=0; b<IB.length; b++)
	 	   {
				 Random random = new Random();
				 
				 
				long AN = (long)(Math.random()* 10000000 )+80000000;
				long AN2 = (long)(Math.random()* 10000000 )+80000000 ; 
			
				
				String IBAN= IB[b] + AN + AN2;
				String Username = Nachnamen[a]+AN;

		    insertSql = "INSERT INTO Kunde (Username, IBAN) VALUES ('"+Username+"','"+IBAN+"')";
    	  stmt.executeUpdate(insertSql);
	      }
	      
		  }
		
		}
}catch(Exception e)
	{
	 	System.err.println("Error(Bestellung):"+e.getMessage());
  }


//Mitarbeiter 400 - 200 Frontoffice - 200 Backoffice- Vorgesetzter
try
{
	String insertSql="";
	String insertSqlfo="";
	String insertSqlbo="";
	String insertSqlvo="";
	int id=1;
	 	for(int i=0; i<2; i++)
		{
	 	  for(int a=0; a<Nachnamen.length; a++)
	 	  {
	 	   for(int b=0; b<Vornamen.length; b++)
	 	   {
					
				String Name= Nachnamen[a] +' '+ Vornamen[b];
				
			insertSql = "INSERT INTO Support_MA (Nachname) VALUES ('"+Name+"')";
    	  	stmt.executeUpdate(insertSql);
	      }
	      
			}
		
		for(int j=0; j<200; ++j)	
		{
			if(i<1)
				{	
					int skill = (int)(Math.random()* 5)+1;
						insertSqlfo="INSERT INTO front_office_ma (Mitarbeiter_ID, Soft_Skills) VALUES ( '"+id+"','"+skill+"')";
						stmt.executeUpdate(insertSqlfo);	
				}else
				 {
						String at= "@amazon.com";
						 String mail= id+at;
						  int tskill = (int)(Math.random()* 5)+1;
						   insertSqlbo="INSERT INTO back_office_ma (Mitarbeiter_ID, Email, Techical_Skills) VALUES ( '"+id+"', '"+mail+"','"+tskill+"')";
							  stmt.executeUpdate(insertSqlbo);
				 }

				 ++id;
		
				}

			}		
			
				
			int m=2;	
			for(int q=1;q<=20;++q)
			{
				for(int v=0;v<19;++v)
				{
					if(m == 401){break;}
					insertSqlvo= "INSERT INTO Vorgesetzter(Mitarbeiter_ID1,Mitarbeiter_ID2) VALUES ( '"+q+"','"+m+"')";
						stmt.executeUpdate(insertSqlvo);
							++m;	
				}

			}	
				
}catch(Exception e)
	{
	 	System.err.println("Error(Mitarbeiter):"+e.getMessage());
	}
	
//Bestellung-Bearbeitet 200
try
{
		String insertSql="";
		String insertSql2="";
		for(int i=1; i<=200; ++i)
		{
			insertSql="INSERT INTO Bestellung (Artikel_NR) VALUES('"+i+"')";
			insertSql2="INSERT INTO Bearbeitet (Artikel_NR, Mitarbeiter_ID) VALUES('"+i+"','"+i+"')";
			stmt.executeUpdate(insertSql);
			stmt.executeUpdate(insertSql2);
		}
	}catch(Exception e)
	 {
		System.err.println("Error(Bestellung-Bearbeitet):"+e.getMessage());
	 }

	 //Bezahlung 200
	try
	{
		String insertSql="";
		for(int i=0; i<40; ++i)
		{
			for(int a=0; a<Zahlungsart.length; ++a)
			{
			insertSql="INSERT INTO Bezahlung (Zahlungsart) VALUES('"+Zahlungsart[a]+"')";
			stmt.executeUpdate(insertSql);
			}
		}
	}catch(Exception e)
	 {
		System.err.println("Error(Bezahlung):"+e.getMessage());
	 }

//Taetigt 200
try
{
		String insertSql="";
		for(int i=1; i<=200; ++i)
		{
			  Random random = new Random();
	    	int KDNR = (int)(Math.random()*100)+900; 

			insertSql="INSERT INTO Taetigt (Artikel_Nr, KD_NR) VALUES('"+i+"','"+KDNR+"')";
			stmt.executeUpdate(insertSql);
		}
	}catch(Exception e)
	 {
		System.err.println("Error(Taetigt):"+e.getMessage());
	 }


      // check number of datasets in artikel table
      ResultSet rs = stmt.executeQuery("SELECT COUNT(*) FROM artikel");
      if (rs.next()) {
        int count = rs.getInt(1);
        System.out.println("Number of datasets(Artikel): " + count);
			}
			
			
      // check number of datasets in kunde table
      ResultSet rs2 = stmt.executeQuery("SELECT COUNT(*) FROM kunde");
      if (rs2.next()) {
        int count = rs2.getInt(1);
        System.out.println("Number of datasets(Kunde): " + count);
      }

			 // check number of datasets in mitarbeiter table
			 ResultSet rs3 = stmt.executeQuery("SELECT COUNT(*) FROM Support_MA");
			 if (rs3.next()) {
				 int count = rs3.getInt(1);
				 System.out.println("Number of datasets(Mitarbeiter): " + count);
			 }

			  // check number of datasets in front_office_ma table
				ResultSet rs31 = stmt.executeQuery("SELECT COUNT(*) FROM front_office_ma");
				if (rs31.next()) {
					int count = rs31.getInt(1);
					System.out.println("Number of datasets(front_office_ma): " + count);
				}

				 // check number of datasets in back_office_ma table
				 ResultSet rs32 = stmt.executeQuery("SELECT COUNT(*) FROM front_office_ma");
				 if (rs32.next()) {
					 int count = rs32.getInt(1);
					 System.out.println("Number of datasets(back_office_ma): " + count);
				 }


                   // check number of datasets in back_office_ma table
				 	ResultSet rs33 = stmt.executeQuery("SELECT COUNT(*) FROM Vorgesetzter");
				 	if (rs33.next()) {
					 int count = rs33.getInt(1);
					 System.out.println("Number of datasets(Vorgesetzter): " + count);
					 }

					 // check number of datasets in Bestellung table
			 		ResultSet rs4 = stmt.executeQuery("SELECT COUNT(*) FROM Bestellung");
			 		if (rs4.next()) {
					 int count = rs4.getInt(1);
				 		System.out.println("Number of datasets(Bestellung): " + count);
			 		}

			 
					 // check number of datasets in Bearbeitet table
			 		ResultSet rs41 = stmt.executeQuery("SELECT COUNT(*) FROM Bearbeitet");
			 			if (rs41.next()) {
				 		int count = rs41.getInt(1);
				 			System.out.println("Number of datasets(Bearbeitet): " + count);
			 		}

			 			// check number of datasets in bezahlung table
			 			ResultSet rs5 = stmt.executeQuery("SELECT COUNT(*) FROM Bezahlung");
			 			if (rs5.next()) {
						 int count = rs5.getInt(1);
							 System.out.println("Number of datasets(Bezahlung): " + count);
						 }
				
						 // check number of datasets in taetigt table
						  ResultSet rs6 = stmt.executeQuery("SELECT COUNT(*) FROM Taetigt");
						 if (rs6.next()) {
							 int count = rs6.getInt(1);
							 System.out.println("Number of datasets(Taetigt): " + count);
						 }

      // clean up connections
      rs.close();
      stmt.close();
      con.close();

    } catch (Exception e) {
      System.err.println(e.getMessage());
		}
  }
}
