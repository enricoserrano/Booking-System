<!-- Enrico Serrano 19071299-->
<!-- booking.php contains backend for booking.html, it gets the data from the form, establishes communication with database, creates a table if needed and inserts data. It also shows the receipt for the booking-->
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel = "stylesheet" type = "text/css" href = "styles.css"/>
      <title>Booking Process Page</title>
   </head>
   <body>
      <?php
         require_once('../../conf/connectionInfo.php');
         
         $cname = $_POST['cname'];
         $phone = $_POST['phone'];
         $unumber = $_POST['unumber'];
         $snumber = $_POST['snumber'];
         $stname = $_POST['stname'];
         $sbname = $_POST['sbname'];
         $dsbname = $_POST['dsbname'];
         $date = $_POST['date'];
         $time = $_POST['time'];
         
         $establishCon = mysqli_connect($hostName, $userName, $password, $dbName);
         //Checks if connection is successful if not create an error message
         if(!$establishCon) {
            echo "<p>Failed to establish connection! Please try again</p>";
            exit();
         } else {
            //If database connection is succcessful, begin creating the table
            $checkTable = mysqli_query($establishCon, 'SELECT * from `bookingTable`;');
         
            //If the table doesnt exists, create the table
            if(!$checkTable) {
            $createTable = "CREATE TABLE bookingTable (bookingid VARCHAR(20) PRIMARY KEY, cname VARCHAR(50), phone INT(12), unumber VARCHAR(10), snumber INT(4), stname VARCHAR(50), sbname VARCHAR(50), dsbname VARCHAR(50), pickupdatetime DATETIME, bookingstatus VARCHAR(20) DEFAULT 'Unassigned');";
         
            $initialiseCreate = mysqli_query($establishCon, $createTable);
            }
            //Counts the number of entries in the database, appends 1, and will be used as a primary number for booking reference number
            $countIndexSearch = "SELECT COUNT(*) FROM `bookingTable`";
            $initialiseCount = mysqli_query($establishCon, $countIndexSearch);
            $rowCount = mysqli_fetch_assoc($initialiseCount);
            $countIndex = $rowCount["COUNT(*)"] + 1;
            $countIndexString = "BRN".str_pad($countIndex,5,"0",STR_PAD_LEFT);
         
            //Inserts data into table
            $insertData = "INSERT INTO `bookingTable` (`bookingid`, `cname`, `phone`, `unumber`, `snumber`, `stname`, `sbname`, `dsbname`, `pickupdatetime`) VALUES ('$countIndexString','$cname', '$phone', '$unumber', '$snumber', '$stname', '$sbname', '$dsbname', '$date + $time');";
            $initialiseInsert = mysqli_query($establishCon, $insertData);
         
            //Checks if data insertion is succesful, if successful show a receipt, if not show an error message to the user
            if(!$initialiseInsert) {
                echo "<p>There is an error with data insertion! Please try again</p>";
            } else {
               $checkbookingId = "SELECT `bookingid` from  `bookingTable` ORDER BY `bookingid` DESC LIMIT 1";
               $getbookingID = mysqli_query($establishCon, $checkbookingId);
         
               $row = mysqli_fetch_assoc($getbookingID);
               $bookingID = $row["bookingid"];
         
               echo "<h3>Thank you for your booking</h3>";
               echo "Booking reference number: ".$bookingID;
               echo "<p>Pickup time: $time</p>";
               echo "Pickup date: ".date("d/m/Y", strtotime($date));
            }
         }
         mysqli_close($establishCon);
         ?>
   </body>
</html>