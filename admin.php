<!-- Enrico Serrano 19071299-->
<!-- admin.php handles the form from admin.html, establishes connection, and sends the information via AJAX. -->
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Booking Process</title>
      <script type="text/javascript" src="xhr.js"></script>
      <script type="text/javascript" src="adminajax.js"> </script>
   </head>
   <body>
      <?php
         require_once('../../conf/connectionInfo.php');
         
         $bsearch = $_POST['bsearch'];
         
         $establishCon = mysqli_connect($hostName, $userName, $password, $dbName);
         
         //This checks the 'assign' button id and updates the value in the database
         if (isset($_GET["bookingid"])) {  
             $bookingid = $_GET["bookingid"];  
             $queryAssign = "UPDATE `bookingTable` SET `bookingstatus` = 'Assigned' WHERE `bookingid` = '$bookingid'";
             $runAssign = mysqli_query($establishCon,$queryAssign); 
             if ($runAssign) {  
                 //echo '<script type="text/javascript">','assignMessage("added");','</script>';
                 //setStatus("true");
                 //header('location:admin.html');  
                 header('Location:admin.html' . $_SERVER['HTTP_REFERER']);
                 exit;
             }else{  
                 echo "Error: ".mysqli_error($establishCon);  
             }  
         }  
         
         if(!$establishCon) {
             echo "<p>Failed to establish connection! Please try again</p>";
             exit();
         } else {
             $checkTable = mysqli_query($establishCon, 'SELECT * from `bookingTable`;');
         
             //Checks if the table exists, if not it creates a table
             if(!$checkTable) {
                 $createTable = "CREATE TABLE bookingTable (bookingid VARCHAR(20) PRIMARY KEY, cname VARCHAR(50), phone INT(12), unumber VARCHAR(10), snumber INT(4), stname VARCHAR(50), sbname VARCHAR(50), dsbname VARCHAR(50), pickupdatetime DATETIME, bookingstatus VARCHAR(20) DEFAULT 'Unassigned');";
           
                 $initialiseCreate = mysqli_query($establishCon, $createTable);
             }
         
             //Fetches from database based on the user input from the form
             $searchBooking = mysqli_query($establishCon, "SELECT * from `bookingTable` where `bookingid` = '$bsearch'");
             
             //If the database cannot fetch the information, it will display an error
             if(!$searchBooking) {
                 echo "<p>There is something wrong with the query</p>";
             } else {
                 //This will be ran if the field is empty and the admin presses 'submit', this will show the entries within 2 hours from the current time
                 if(empty($_POST['bsearch'])) {
                     $ifemptysearch = mysqli_query($establishCon, "SELECT  * from `bookingTable` where `pickupdatetime` between NOW() and (NOW() + INTERVAL 2 HOUR) AND `bookingstatus` = 'Unassigned'");
                     if (mysqli_num_rows($ifemptysearch) > 0) {
                         //Prints query result if the admin leaves the input field empty (query result are within 2 hours of current system time)
                         echo "<table border=\"1\">";
                         echo "<tr>\n"
                              ."<th scope=\"col\">Booking Reference Number</th>\n"
                              ."<th scope=\"col\">Customer Name</th>\n"
                              ."<th scope=\"col\">Phone</th>\n"
                              ."<th scope=\"col\">Pickup Suburb</th>\n"
                              ."<th scope=\"col\">Destination Suburb</th>\n"
                              ."<th scope=\"col\">Pickup Date and Time</th>\n"
                              ."<th scope=\"col\">Status</th>\n"
                              ."<th scope=\"col\">Assign</th>\n"
                              ."</tr>\n";
                         while ($row = mysqli_fetch_assoc($ifemptysearch)){
                             echo "<tr>";
                             echo "<td>",$row["bookingid"],"</td>";
                             echo "<td>",$row["cname"],"</td>";
                             echo "<td>",$row["phone"],"</td>";
                             echo "<td>",$row["sbname"],"</td>";
                             echo "<td>",$row["dsbname"],"</td>";
                             echo "<td>",$row["pickupdatetime"],"</td>";
                             echo "<td>",$row["bookingstatus"],"</td>";
                             //This disables the button after the admin clicks assigned or if the status is alrady assigned in the database
                             if($row["bookingstatus"] == "Assigned") {
                                 echo "<td>","<a href='admin.php?bookingid=".$row["bookingid"]."' id='buttondisabled' disabled='disabled'>Assign</a>","</td>";
                             } else {
                                 echo "<td>","<a onClick='showMessage()' href='admin.php?bookingid=".$row["bookingid"]."' id='button'>Assign</a>","</td>";
                             }
                             echo "</tr>";
                         }
                         } else {
                             echo "<p>There are no records of booking in the next 2 hours</p>";
                         }
                         echo "</table>";
                 } else {
                     //This will be ran if the user input booking reference, matches exactly from the database
                     if (mysqli_num_rows($searchBooking) > 0) {
                         //Prints query result if it matches a refrence id in the database
                         echo "<table border=\"1\">";
                         echo "<tr>\n"
                              ."<th scope=\"col\">Booking Reference Number</th>\n"
                              ."<th scope=\"col\">Customer Name</th>\n"
                              ."<th scope=\"col\">Phone</th>\n"
                              ."<th scope=\"col\">Pickup Suburb</th>\n"
                              ."<th scope=\"col\">Destination Suburb</th>\n"
                              ."<th scope=\"col\">Pickup Date and Time</th>\n"
                              ."<th scope=\"col\">Status</th>\n"
                              ."<th scope=\"col\">Assign</th>\n"
                              ."</tr>\n";
                         while ($row = mysqli_fetch_assoc($searchBooking)){
                             echo "<tr>";
                             echo "<td>",$row["bookingid"],"</td>";
                             echo "<td>",$row["cname"],"</td>";
                             echo "<td>",$row["phone"],"</td>";
                             echo "<td>",$row["sbname"],"</td>";
                             echo "<td>",$row["dsbname"],"</td>";
                             echo "<td>",$row["pickupdatetime"],"</td>";
                             echo "<td>",$row["bookingstatus"],"</td>";
                             //This disables the button after the admin clicks assigned or if the status is alrady assigned in the database
                             if($row["bookingstatus"] == "Assigned") {
                                 echo "<td>","<a href='admin.php?bookingid=".$row["bookingid"]."' id='buttondisabled' disabled='disabled'>Assign</a>","</td>";
                             } else {
                                 echo "<td>","<a onClick='showMessage()' href='admin.php?bookingid=".$row["bookingid"]."' id='button' >Assign</a>","</td>";
                             }
                             echo "</tr>";
                         }
                         } else {
                             //An error message is provided is there is no matching reference number
                             echo "<p>There is no result for your query</p>";
                         }
                         echo "</table>";
                 }
         // Frees up the memory, after using the result pointer
         mysqli_free_result($searchBooking);
             }
         }
         ?>
   </body>
</html>