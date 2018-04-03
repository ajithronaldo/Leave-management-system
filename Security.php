<?php 
    session_start();
    $id = isset($_SESSION['sess_user_id']);
    $role = isset($_SESSION['sess_userrole']);
    $name = isset($_SESSION['sess_username']);
    if(!isset($_SESSION['sess_username'])){
      header('Location: index.php?err=2');
    }
    if($_SESSION['sess_userrole'] != "Security"){
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
      .tablink {
        width: 50%;
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
      <button class="tablink" onclick="openPage('current_leave', this)"  id="defaultOpen">Today's Leave</button>
      <button class="tablink" onclick="openPage('upcoming_leave', this)" >Hosteller's Leave</button>
      </div>

      <!-- Current Leave Starts -->
      <div id="current_leave" class="tabcontent">
        <h3><center>Today's Student Leave Request</center></h3>
        <?php
          require "config/Connection.php";

          $getStudentLeaveQuery = 'SELECT * FROM studentleave where FromDate = :dat and Principal_Approval = "Approved" ORDER BY AppliedOn';
          $getStudentLeaveProcess = $dbh->prepare($getStudentLeaveQuery);
          $getStudentLeaveProcess->execute(array(':dat' => date("Y-m-d")));
          
          if($getStudentLeaveProcess->rowCount() > 0){
            $lvlcnt = $getStudentLeaveProcess->rowCount();
            print "<table class='table table-striped table-hover'>
                    <thead>
                      <tr>
                        <th>S.No.</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Student Dept</th>
                        <th>Student Type</th>
                        <th>Approval Status</th>
                      </tr>
                    </thead>
                    <tbody>";
                    $serial = 1; 

            while($row = $getStudentLeaveProcess->fetch(PDO::FETCH_ASSOC)) {
              print "<tr>";
              print "<td> ". $serial . "</td>";
              print "<td> ". $row["StudentID"] . "</td>";
              print "<td> ". $row["StudentName"]. "</td>";
              print "<td> ". $row["StudentDept"]. "</td>";
              print "<td> ". $row["student_type"]. "</td>";
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
      <!-- Current Leave Ends -->

      <!-- upcoming Leaves Starts -->
      <div id="upcoming_leave" class="tabcontent">
        <center><h3>Hostel Student Leave Requests</h3></center>
        <?php
          require "config/Connection.php";

          $getStudentLeaveQuery = 'SELECT * FROM studentleave where FromDate >= :dat and student_type = "Hosteller"  and Principal_Approval = "Approved" ORDER BY AppliedOn';
          $getStudentLeaveProcess = $dbh->prepare($getStudentLeaveQuery);
          $getStudentLeaveProcess->execute(array(':dat' => date("Y-m-d")));
          
          if($getStudentLeaveProcess->rowCount() > 0){
            print "<table class='table table-striped table-hover'>
                    <thead>
                      <tr>
                        <th>S.No.</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Student Dept</th>
                        <th>Approval Status</th>
                      </tr>
                    </thead>
                    <tbody>";
                    $serial = 1; 

            while($row = $getStudentLeaveProcess->fetch(PDO::FETCH_ASSOC)) {
              print "<tr>";
              print "<td> ". $serial . "</td>";
              print "<td> ". $row["StudentID"] . "</td>";
              print "<td> ". $row["StudentName"]. "</td>";
              print "<td> ". $row["StudentDept"]. "</td>";
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
      <!-- upcoming Leaves Ends -->

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


