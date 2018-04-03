<?php 
    session_start();
    $id = isset($_SESSION['sess_user_id']);
    $role = isset($_SESSION['sess_userrole']);
    $name = isset($_SESSION['sess_username']);
    if(!isset($_SESSION['sess_username'])){
      header('Location: index.php?err=2');
    }
    if($_SESSION['sess_userrole'] != "Student"){
        header('Location: index.php?err=2');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leave Management System</title>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/userStyles.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>

    <style>
      .tablink {
         width: 33%;
      }
      .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        border-top: none;
      }
    </style>
  </head>
  <body>
    
    <div class="navbar navbar-default navbar-fixed-top" role="navigation" style="background: #66A3FF; font-weight: bold; font-size: 16px;">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Leave Management System</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><I>Leave Management System</I></a>
        </div>

        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#passwordModal"><?php echo $_SESSION['sess_username'];?></button></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Password Modal Starts -->
    
    <div class="modal fade" id="passwordModal" role="dialog">
      <div class="modal-dialog">
      
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Change Password</h4>
          </div>
          <div class="modal-body">
            <form action="">
              <div class="form-group">
                <label for="email">Password:</label>
                <input type="password" class="form-control" id="pwd1">
              </div>
              <div class="form-group">
                <label for="pwd">Conform Password:</label>
                <input type="password" class="form-control" id="pwd2">
              </div>
              <button type="button" class="col-md-offset-4 col-md-4 btn btn-default" onclick="updatePassword()">Update</button>
            </form>
            <br><br>
            <div class="alert alert-info">
              <strong>Info!</strong> <div id = "passwordStatus"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
        
      </div>
    </div>
  
    <!-- Password Modal Ends -->

    <!-- Main Block Starts -->

    <div class="container-fluid">
      <div class="tab">
      <button class="tablink" onclick="openPage('apply_leave', this)"  id="defaultOpen">Apply Leave</button>
      <button class="tablink" onclick="openPage('history', this)" >History</button>
      <button class="tablink" onclick="openPage('status', this)">Status</button>
      </div>

      <!-- Leave Form Starts -->
      <div id="apply_leave" class="tabcontent">
        <h3><center>Leave Request Form</center></h3>
        <form method = "post" action = "">
          <table class="table" style="border-top: none;">
            <tr>
               <td>Roll No.</td> 
               <td><?php echo $_SESSION['sess_user_id'];?></td>
               <td>Date :</td>
               <td><?php echo date("d/m/Y") ?></td>
            </tr>
  
            <tr>
               <td>Name:</td>
               <td><?php echo $_SESSION['sess_username'];?></td>
            </tr>
            
            <tr>
               <td>From :</td>
               <td><input type="date" placeholder="dd/mm/yyyy" name="fromDate" id="fromDate"></td>
            </tr>

            <tr>
               <td>To :</td>
               <td><input type="date" placeholder="dd/mm/yyyy" name="toDate" id="toDate"></td>            
               <td>FN :&emsp;<input type="checkbox" name="FN"></td>
               <td>AN :&emsp;<input type="checkbox" name="AN"></td>
            </tr>
            
            <tr>
               <td>Reason For leave:</td>
               <td><textarea name = "Reason" placeholder = "Reason" rows = "4" cols = "40" id="Reason"></textarea></td>
            </tr>
          </table>
          <center><input type = "submit" name = "submit" id="subBtn" value = "submit"> </center>         
        </form>

        <?php
          require 'config/Connection.php';
          $id = $_SESSION['sess_user_id'];
          $name = $_SESSION['sess_username'];

          if(isset($_POST["submit"])){
            extract($_POST);
            
            $from = $_POST['fromDate'];
            $to = $_POST['toDate'];
            $Reason = $_POST['Reason'];

            echo '<script>console.log('.$from.');</script>';


            $dept="";
            $type="";

            // Get Student Department
            $getDeptQuery = 'SELECT * FROM studentdetails WHERE StudentID = :id';
            $getDeptResult = $dbh->prepare($getDeptQuery);
            $getDeptResult->execute(array(':id' => $id));
            if($getDeptResult->rowCount() > 0){
              $row = $getDeptResult->fetch(PDO::FETCH_ASSOC);
              $dept = $row['StudentDept'];
              $type = $row['student_type'];
              $gen = $row['Gender'];
            }
            
            // Insert into Student Leave Record
            $leaveInsertQuery='INSERT into studentleave (StudentID,StudentName,StudentDept,Gender,student_type,FromDate,ToDate,Reason) values (:id,:name,:dept,:gen,:type,:fro,:to,:Reason)';
            $leaveInsertResult = $dbh->prepare($leaveInsertQuery);
            $leaveInsertResult->execute(array(':id' => $id, ':name' => $name, ':dept' => $dept, ':gen' => $gen, ':type' => $type, ':fro' => $from, ':to' => $to, ':Reason' => $Reason ));

            
          }
          
        ?>

      </div>
      <!-- Leave Form Ends -->

      <!-- History Starts -->
      <div id="history" class="tabcontent">
        <center><h3>Your Leave History</h3></center>
        <?php
          require "config/Connection.php";

          $id = $_SESSION['sess_user_id'];
          
          $historyQuery = 'SELECT * FROM studentleave where StudentID = :id ORDER BY AppliedOn DESC';
          $historyResult = $dbh->prepare($historyQuery);
          $historyResult->execute(array(':id' => $id));
          if($historyResult->rowCount() > 0){

            print "<table class='table table-striped'>
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Student ID</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Reason</th>
                <th>Applied On</th>
                <th>Staff Approval</th>
                <th>HOD Approval</th>
                <th>Principal Approval</th>
              </tr>
            </thead>
            <tbody>";
            $serial = 1; 
            
            while($row = $historyResult->fetch(PDO::FETCH_ASSOC)) {
                print "<tr>";
                print "<td> ". $serial . "</td>";
                print "<td> ". $row["StudentID"] . "</td>";
                print "<td> ". $row["FromDate"]. "</td>";
                print "<td> ". $row["ToDate"]. "</td>";
                print "<td> ". $row["Reason"]. "</td>";
                print "<td> ". $row["AppliedOn"]. "</td>";
                print "<td> ". $row["Mentor_Approval"]. "</td>";
                print "<td> ". $row["Hod_Approval"]. "</td>";
                print "<td> ". $row["Principal_Approval"]. "</td>"; 
                print "</tr>";
                $serial = $serial + 1;
            }
            print "</tbody>
                  </table>";
          }else{
            print "No Record Found..!!!! ";
          }
        ?>
      </div>
      <!-- History Ends -->

      <!-- Status Starts -->
      <div id="status" class="tabcontent">
        <center><h3>Status About Your Recent Leave Request</h3></center>
        <?php
          require "config/Connection.php";

          $id = $_SESSION['sess_user_id'];
          
          $statusQuery = 'SELECT * FROM studentleave where StudentID = :id ORDER BY AppliedOn DESC LIMIT 1';
          $statusResult = $dbh->prepare($statusQuery);
          $statusResult->execute(array(':id' => $id));
          if($statusResult->rowCount() > 0){

            print "<table class='table table-bordered'>
            <thead>
              <tr>
                <th>Student ID</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Reason</th>
                <th>Applied On</th>
                <th>Staff Approval</th>
                <th>HOD Approval</th>
                <th>Principal Approval</th>
              </tr>
            </thead>
            <tbody>";
            
            while($row = $statusResult->fetch(PDO::FETCH_ASSOC)) {
                print "<tr class='info'>";
                print "<td> ". $row["StudentID"] . "</td>";
                print "<td> ". $row["FromDate"]. "</td>";
                print "<td> ". $row["ToDate"]. "</td>";
                print "<td> ". $row["Reason"]. "</td>";
                print "<td> ". $row["AppliedOn"]. "</td>";
                print "<td> ". $row["Mentor_Approval"]. "</td>";
                print "<td> ". $row["Hod_Approval"]. "</td>";
                print "<td> ". $row["Principal_Approval"]. "</td>"; 
                print "</tr>";
            }
            print "</tbody>
                  </table>";
          }else{
            print "No Record Found..!!!! ";
          }
        ?>
      </div>
      <!-- Status Ends -->

    </div>

    <!-- Main Block Ends -->

    <script>
      function openPage(pageName,elmnt) {
          var i, tabcontent, tablinks;
          tabcontent = document.getElementsByClassName("tabcontent");
          for (i = 0; i < tabcontent.length; i++) {
              tabcontent[i].style.display = "none";
          }
          tablinks = document.getElementsByClassName("tablink");
          for (i = 0; i < tablinks.length; i++) {
              tablinks[i].style.backgroundColor = "";
          }
          document.getElementById(pageName).style.display = "block";
          elmnt.style.backgroundColor = '#66a3ff';

      }

      document.getElementById("defaultOpen").click();

      var dateToday = new Date();
      var dates = $("#from, #to").datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 1,
          minDate: dateToday,
          onSelect: function(selectedDate) {
              var option = this.id == "from" ? "minDate" : "maxDate",
                  instance = $(this).data("datepicker"),
                  date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                  
              dates.not(this).datepicker("option", option, date);
          }
      });

      function updatePassword() {
        var id = '<?php echo $_SESSION['sess_user_id']; ?>';
        var password = document.getElementById("pwd1").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("passwordStatus").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Controllers/password.php?id="+id+"&pass="+password, true);
        xmlhttp.send();
      }
    </script>

  </body>
</html>


