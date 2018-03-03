<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username'])){
      header('Location: index.php?err=2');
    }
    if($role!="Principal"){
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

      <div class="container homepage">
      <div class="tab">
        <a href="javascript:void(0)" class="tablinks" onclick="openEvent(event, 'Student_Request')">Student Request</a>
        <a href="javascript:void(0)" class="tablinks" onclick="openEvent(event, 'Staff_Request')">Staff Request</a>
        <a href="javascript:void(0)" class="tablinks" onclick="openEvent(event, 'Hod_Request')">Hod Request</a>
      </div>

      <div id="Student_Request" class="tabcontent">
        <h3>Student Requests</h3>
        <?php
require "database-config.php";

$id = $_SESSION['sess_user_id'];

echo $id;

  $getstud = 'SELECT * FROM studentleave where Hod_Approval = "Approved" and Principal_Approval = "Pending" ORDER BY AppliedOn DESC';

  $result = $dbh->query($getstud);

if ($result->rowCount() > 0) {

  /*$showleave = $dbh->prepare($getstud);

  $showleave->execute(array(':dept' => $dept ));

  if($showleave->rowCount() > 0){ */

    print "<table border = 1 cellspacing = 5px cellpadding = 5% ; align = center>
    <tr> <th> Student ID </th> <th> Student Name </th> <th> Student Dept </th>  <th> From Date </th> <th>To Date</th> <th> Reason</th> <th>Applied On</th> <th>Staff Approved </th> <th>HOD Approved </th> <th>Principal Approval </th> </tr>";

    
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        print "<tr>";
        print "<td> ". $row["StudentID"] . "</td>";
        print "<td> ". $row["StudentName"]. "</td>";
        print "<td> ". $row["StudentDept"]. "</td>";
        print "<td> ". $row["FromDate"]. "</td>";
        print "<td> ". $row["ToDate"]. "</td>";
        print "<td> ". $row["Reason"]. "</td>";
        print "<td> ". $row["AppliedOn"]. "</td>";
        print "<td> ". $row["Mentor_Approval"]. "</td>";
        print "<td> ". $row["Hod_Approval"]. "</td>";
        print "<td> <form action='' method='POST'><input type='hidden' name='tempId' value='".$row["AppliedOn"]."'/>
                    <input type='submit' name='btn-approve' value='Approve' />
                    <input type='submit' name='btn-reject' value='Reject' />
                    <form></td>";        
        print "</tr>";
      }
      print "</table>";

    }else{
        print "No Record Found..!!!! ";
  }

   ?>
<?php
    require 'database-config.php';
    if(isset($_POST["btn-approve"])){
        extract($_POST);
        $tempid = $_POST['tempId'];
        $sql = "UPDATE studentleave SET   Principal_Approval ='Approved' WHERE AppliedOn = :temp";

      $stmt = $dbh->prepare($sql);

      $stmt->execute(array(':temp' => $tempid));
    }


   ?>

   <?php
    require 'database-config.php';
    if(isset($_POST["btn-reject"])){
        extract($_POST);
        $tempid = $_POST['tempId'];
        $sql = "UPDATE studentleave SET Principal_Approval='Rejected' WHERE AppliedOn = :temp";

      $stmt = $dbh->prepare($sql);

      $stmt->execute(array(':temp' => $tempid));

    }

   ?>

      </div>

<div id="Staff_Request" class="tabcontent">
        <h3>Staff Requests</h3>
        <?php
require "database-config.php";

$id = $_SESSION['sess_user_id'];

echo $id;

  $getstud = 'SELECT * FROM staffleave where Hod_Approval = "Approved" and Principal_Approval = "Pending" and Role !="HOD" ORDER BY AppliedOn DESC';

  $showleave = $dbh->query($getstud);

  if($showleave->rowCount() > 0){

    print "<table border = 1 cellspacing = 5px cellpadding = 5% ; align = center>
    <tr> <th> Student ID </th> <th> Student Name </th> <th> Student Dept </th>  <th> From Date </th> <th>To Date</th> <th> Reason</th> <th>Applied On</th> <th>leave Type </th> <th>Faculty Altered </th> <th>HOD Approved</th> <th>Principal Approval</th> </tr>";

    
    while($row = $showleave->fetch(PDO::FETCH_ASSOC)) {
        print "<tr>";
        print "<td> ". $row["StaffID"] . "</td>";
        print "<td> ". $row["StaffName"]. "</td>";
        print "<td> ". $row["StaffDept"]. "</td>";
        print "<td> ". $row["FromDate"]. "</td>";
        print "<td> ". $row["ToDate"]. "</td>";
        print "<td> ". $row["Reason"]. "</td>";
        print "<td> ". $row["AppliedOn"]. "</td>";
        print "<td> ". $row["LeaveType"]. "</td>";
        print "<td> ". $row["AlteredFaculty"]. "</td>";
        print "<td> ". $row["Hod_Approval"]. "</td>";
        print "<td> <form action='' method='POST'><input type='hidden' name='tempId' value='".$row["AppliedOn"]."'/>
                    <input type='submit' name='btn-staff-approve' value='Approve' />
                    <input type='submit' name='btn-staff-reject' value='Reject' />
                    <form></td>";
        print "</tr>";
      }
      print "</table>";
    }else{
        print "No Record Found..!!!! ";
  }
   ?>

   <?php
    require 'database-config.php';
    if(isset($_POST["btn-staff-approve"])){
        extract($_POST);
        $tempid = $_POST['tempId'];
        $sql = "UPDATE staffleave SET Principal_Approval='Approved' WHERE AppliedOn = :temp";

      $stmt = $dbh->prepare($sql);

      $stmt->execute(array(':temp' => $tempid));

      $getstud = 'SELECT * FROM studentleave where AppliedOn = :temp';

      $result = $dbh->prepare($getstud);
      $result->execute(array(':temp' => $tempid));
      $page_title='Leave Approved';
//mail start
$message1='<table width=100% border=0 border-color:none cellspacing=3 cellpadding=3 class=text style="font-family:Arial; line-height:160% word-spacing:0.4em font-size:18px; border: 1px solid" bgcolor="#CCCCCC" color:"#fffff">
<tr ><td colspan="4"  align="center" bgcolor="#CCCCCC"><strong>'.$page_title.'</strong></td></tr>
<tr><td>Roll No</td><td>:</td><td >'.$row["StudentID"].'</td></tr>
<tr><td>Name</td><td>:</td><td>'.$row["StudentName"].'</td></tr>
<tr><td>Department</td><td>:</td><td>'.$row["StudentDept"].'</td></tr>
<tr><td>From Date</td><td>:</td><td>'.$row["FromDate"].'</td></tr>
<tr><td>To</td><td>:</td><td>'.$row["To Date"].'</td></tr>
<tr><td>Reason</td><td>:</td><td>'.$row["Reason"].'</td></tr>
<tr><td>Status</td><td>:</td><td>'.$row["Principal_Approval"].'</td></tr>

</table>';
$email = 'principal@kgkite.ac.in';
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: '.$email;
Mail("ganesh.m635@outlook.com","$page_title","$message1","$headers");

    }


   ?>

   <?php
    require 'database-config.php';
    if(isset($_POST["btn-staff-reject"])){
        extract($_POST);
        $tempid = $_POST['tempId'];
        $sql = "UPDATE staffleave SET Principal_Approval='Rejected' WHERE AppliedOn = :temp";

      $stmt = $dbh->prepare($sql);

      $stmt->execute(array(':temp' => $tempid));

    }

   ?>

      </div>

      <div id="Hod_Request" class="tabcontent">
        <h3>Hod Requests</h3>
        <?php
require "database-config.php";

$id = $_SESSION['sess_user_id'];

echo $id;

  $getstud = 'SELECT * FROM staffleave where Principal_Approval = "Pending" and Role ="HOD" ORDER BY AppliedOn DESC';

  $showleave = $dbh->query($getstud);

  if($showleave->rowCount() > 0){

    print "<table border = 1 cellspacing = 5px cellpadding = 5% ; align = center>
    <tr> <th> Student ID </th> <th> Student Name </th> <th> Student Dept </th>  <th> From Date </th> <th>To Date</th> <th> Reason</th> <th>Applied On</th> <th>leave Type </th> <th>Faculty Altered </th> <th>Principal Approval</th> </tr>";

    
    while($row = $showleave->fetch(PDO::FETCH_ASSOC)) {
        print "<tr>";
        print "<td> ". $row["StaffID"] . "</td>";
        print "<td> ". $row["StaffName"]. "</td>";
        print "<td> ". $row["StaffDept"]. "</td>";
        print "<td> ". $row["FromDate"]. "</td>";
        print "<td> ". $row["ToDate"]. "</td>";
        print "<td> ". $row["Reason"]. "</td>";
        print "<td> ". $row["AppliedOn"]. "</td>";
        print "<td> ". $row["LeaveType"]. "</td>";
        print "<td> ". $row["AlteredFaculty"]. "</td>";
        print "<td> <form action='' method='POST'><input type='hidden' name='tempId' value='".$row["AppliedOn"]."'/>
                    <input type='submit' name='btn-hod-approve' value='Approve' />
                    <input type='submit' name='btn-hod-reject' value='Reject' />
                    <form></td>";
        print "</tr>";
      }
      print "</table>";
    }else{
        print "No Record Found..!!!! ";
  }
   ?>

   <?php
    require 'database-config.php';
    if(isset($_POST["btn-hod-approve"])){
        extract($_POST);
        $tempid = $_POST['tempId'];
        $sql = "UPDATE staffleave SET Principal_Approval='Approved' WHERE AppliedOn = :temp";

      $stmt = $dbh->prepare($sql);

      $stmt->execute(array(':temp' => $tempid));
    }


   ?>

   <?php
    require 'database-config.php';
    if(isset($_POST["btn-hod-reject"])){
        extract($_POST);
        $tempid = $_POST['tempId'];
        $sql = "UPDATE staffleave SET Principal_Approval='Rejected' WHERE AppliedOn = :temp";

      $stmt = $dbh->prepare($sql);

      $stmt->execute(array(':temp' => $tempid));

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
