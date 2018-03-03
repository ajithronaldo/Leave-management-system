<?php 
    session_start();
    $id = isset($_SESSION['sess_user_id']);
    $role = isset($_SESSION['sess_userrole']);
    $name = isset($_SESSION['sess_username']);
    if(!isset($_SESSION['sess_username'])){
      header('Location: index.php?err=2');
    }
    if($role!="Student"){
        header('Location: index.php?err=2');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSE-Hackathon</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
/* Style the tab */
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the links inside the tab */
div.tab a {
    float: left;
    display: block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of links on hover */
div.tab a:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab a:focus, .active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>
  </head>
  <body>
    
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">My Campus My Idea</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><I>CSE</I></a>
        </div>

        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><?php echo $_SESSION['sess_username'];?></a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
    <br><br><br><br>
    <div class="container homepage">
      <div class="tab">
        <a href="javascript:void(0)" class="tablinks" onclick="openEvent(event, 'Apply_Leave')">Apply Leave</a>
        <a href="javascript:void(0)" class="tablinks" onclick="openEvent(event, 'History')">History</a>
        <a href="javascript:void(0)" class="tablinks" onclick="openEvent(event, 'Status')">Status</a>
      </div>

      <div id="Apply_Leave" class="tabcontent">
      
        <h3>Apply Leave</h3>
        <form method = "post" action = "">
          <table class="table table-bordered table-responsive">
          <center><tr>Leave Form</tr></center>
            <br>
               <td>Roll No.</td> 
               <td><?php echo $_SESSION['sess_user_id'];?></td>
               <td></td><td></td>
               <td>Date :</td>
               <td><?php echo date("d/m/Y") ?></td>

            </tr>
            
            <tr>
               <td>Name:</td>
               <td><?php echo $_SESSION['sess_username'];?></td>
            </tr>
            
            <tr>
               <td>From :</td>
               <td><input type="date" placeholder="dd/mm/yyyy" name="fromDate"></td>
            </tr>

            <tr>
               <td>To :</td>
               <td><input type="date" placeholder="dd/mm/yyyy" name="toDate"></td>
               <td></td>
               <td>FN :</td>
               <td><input type="checkbox" name="FN"></td>
               <td>AN :</td>
               <td><input type="checkbox" name="AN"></td>
            </tr>
            
            <tr>
               <td>Reason For leave:</td>
               <td><textarea name = "Reason" placeholder = "Reason" rows = "4" cols = "40"></textarea></td>
            </tr>
            </table>
              <center><input type = "submit" name = "submit" value = "submit"> </center>         
      </form>
      
      </div>

      <div id="History" class="tabcontent">
        <h3>History</h3>
        <?php
require "database-config.php";

$id = $_SESSION['sess_user_id'];

echo $id;



  $gethist = 'SELECT * FROM studentleave where StudentID = :id ORDER BY AppliedOn DESC';

  $query = $dbh->prepare($gethist);

  $query->execute(array(':id' => $id));

  if($query->rowCount() > 0){

    print "<table border = 1 cellspacing = 5px cellpadding = 5% ; align = center>
    <tr> <th> Student ID </th> <th> Student Dept </th> <th> From Date </th> <th>To Date</th> <th> Reason</th> <th> Applied On</th> <th>Staff Approved </th><th>Hod Approved </th> <th>Principal</th>     </tr>";

    
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        print "<tr>";
        print "<td> ". $row["StudentID"] . "</td>";
        print "<td> ". $row["StudentDept"]. "</td>";
        print "<td> ". $row["FromDate"]. "</td>";
        print "<td> ". $row["ToDate"]. "</td>";
        print "<td> ". $row["Reason"]. "</td>";
        print "<td> ". $row["AppliedOn"]. "</td>";
        print "<td> ". $row["Mentor_Approval"]. "</td>";
        print "<td> ". $row["Hod_Approval"]. "</td>";
        print "<td> ". $row["Principal_Approval"]. "</td>"; 
        print "</tr>";
      }
      print "</table>";
    }else{
        print "No Record Found..!!!! ";
  }
   ?>
      </div>

      <div id="Status" class="tabcontent">
        <h3>Status</h3>
        <?php
require "database-config.php";

$id = $_SESSION['sess_user_id'];

echo $id;



  $gethist = 'SELECT * FROM studentleave where StudentID = :id ORDER BY AppliedOn DESC LIMIT 1';

  $query = $dbh->prepare($gethist);

  $query->execute(array(':id' => $id));

  if($query->rowCount() > 0){

    print "<table border = 1 cellspacing = 5px cellpadding = 5% ; align = center>
    <tr> <th> Student ID </th> <th> Student Dept </th> <th> From Date </th> <th>To Date</th> <th> Reason</th> <th> Applied On</th> <th>Staff Approved </th><th>Hod Approved </th> <th>Principal</th>     </tr>";

    
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        print "<tr>";
        print "<td> ". $row["StudentID"] . "</td>";
        print "<td> ". $row["StudentDept"]. "</td>";
        print "<td> ". $row["FromDate"]. "</td>";
        print "<td> ". $row["ToDate"]. "</td>";
        print "<td> ". $row["Reason"]. "</td>";
        print "<td> ". $row["AppliedOn"]. "</td>";
        print "<td> ". $row["Mentor_Approval"]. "</td>";
        print "<td> ". $row["Hod_Approval"]. "</td>";
        print "<td> ". $row["Principal_Approval"]. "</td>"; 
        print "</tr>";
      }
      print "</table>";
    }else{
        print "No Record Found..!!!! ";
  }
   ?>
      </div>
      
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <script>
      function openEvent(evt, action) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(action).style.display = "block";
    evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

</body>
</html>

<?php
require 'database-config.php';
    $id = $_SESSION['sess_user_id'];
    $name = $_SESSION['sess_username'];


  if(isset($_POST["submit"])){
  extract($_POST);
  
  $from = $_POST['fromDate'];
  $to = $_POST['toDate'];
  $Reason = $_POST['Reason'];

  $dept="";
/*$getdept = "SELECT StudentDept FROM studentdetails WHERE StudentID = $id ";
        $result = mysqli_query($dbh,$getdept);

        if(mysqli_num_rows($result) > 0){
          while($row = mysqli_fetch_assoc($result)){
            $dept = $row["StudentDept"];
          }
        } */


        $getdept = 'SELECT * FROM studentdetails WHERE StudentID = :id';

  $query = $dbh->prepare($getdept);

  $query->execute(array(':id' => $id));

  if($query->rowCount() > 0){

    $row = $query->fetch(PDO::FETCH_ASSOC);

    
    $dept = $row['StudentDept'];
  }
  //$ins = 'INSERT INTO `studentleave`(`StudentID`, `StudentName`, `StudentDept`, `FromDate`, `ToDate`, `Reason`) VALUES ($id,$name,$dept,$from,$to,$Reason)';
  $ins='INSERT into studentleave (StudentID,StudentName,StudentDept,FromDate,ToDate,Reason) values (:id,:name,:dept,:from,:to,:Reason)';
  $query = $dbh->prepare($ins);

  $query->execute(array(':id' => $id, ':name' => $name, ':dept' => $dept, ':from' => $from, ':to' => $to, ':Reason' => $Reason ));

    /*if(mysqli_query($dbh, $ins)){
              echo "Leave Applied SuccessFully..!!";
          }else{
              echo "ERROR: Could not able to execute $ins. " . mysqli_error($dbh);
         }*/
  }
    
?>
