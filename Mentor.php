<?php 
    session_start();
    $id = isset($_SESSION['sess_user_id']);
    $role = isset($_SESSION['sess_userrole']);
    $name = isset($_SESSION['sess_username']);
    if(!isset($_SESSION['sess_username'])){
      header('Location: index.php?err=2');
    }
    if($_SESSION['sess_userrole'] != "Mentor"){
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

    

    <!-- For PDF Exports -->
    <script src="js/jspdf.js"></script>
    <script src="js/jquery-2.1.3.js"></script>
    <script src="js/pdfFromHTML.js"></script>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/userStyles.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/userStyles.css">
    <style>
      .tablink {
        width: 20%;
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
          <ul class="nav navbar-nav navbar-right" id="notification">
            
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
      <button class="tablink" onclick="openPage('leave_request', this)">Student Requests</button>
      <button class="tablink" onclick="openPage('history', this)" >History</button>
      <button class="tablink" onclick="openPage('status', this)">Status</button>
      <button class="tablink" onclick="openPage('report', this)" >Report</button>
      </div>
<!-- Leave Form Starts -->
      <div id="apply_leave" class="tabcontent">
        <h3><center>Leave Request Form</center></h3>
        <form method = "post" action = "">
          <table class="table">
            <tr>
               <td>Employee ID.</td> 
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
               <td><input type="date" placeholder="dd/mm/yyyy" name="fromDate"></td>
            </tr>

            <tr>
               <td>To :</td>
               <td><input type="date" placeholder="dd/mm/yyyy" name="toDate"></td>
               <td>FN :&emsp;<input type="checkbox" name="FN"></td>
               <td>AN :&emsp;<input type="checkbox" name="AN"></td>
            </tr>
            
            <tr>
               <td>Reason For leave:</td>
               <td><textarea name = "Reason" placeholder = "Reason" rows = "4" cols = "40"></textarea></td>
            </tr>

            <tr>
               <td>Faculty Altered :</td>
               <td><input type="text" placeholder="Faculty Altered" name="AlteredFaculty"></td>
            </tr>

            <tr>
               <td>Leave Type :</td>
               <td><input type="radio" name="LeaveType" value="CL">CL 
                  &emsp;<input type="radio" name="LeaveType" value="CLP">CLP
                  &emsp;<input type="radio" name="LeaveType" value="LOP">LOP
                  &emsp;<input type="radio" name="LeaveType" value="ML">ML
               </td>
            </tr>
           </table>
           <center><input type = "submit" name = "submit" id="subBtn" value = "submit"></center>         
        </form>

        <?php
          require 'config/Connection.php';
          $id = $_SESSION['sess_user_id'];
          $name = $_SESSION['sess_username'];
          $role = $_SESSION['sess_userrole'];

          if(isset($_POST["submit"])){
            extract($_POST);
             
            $from = $_POST['fromDate'];
            $to = $_POST['toDate'];
            $Reason = $_POST['Reason'];
            $AlteredFaculty = $_POST['AlteredFaculty'];
            $LeaveType = $_POST['LeaveType'];

            $dept="";

              // Get Staff Deptartment
            $getdept = 'SELECT * FROM staffdetails WHERE StaffID = :id';
            $query = $dbh->prepare($getdept);
            $query->execute(array(':id' => $id));

            if($query->rowCount() > 0){
              $row = $query->fetch(PDO::FETCH_ASSOC);   
              $dept = $row['StaffDept'];
            }
             
            // Insert Staff Leave
            $ins='INSERT into staffleave (StaffID,StaffName,StaffDept,Role,FromDate,ToDate,Reason,AlteredFaculty,LeaveType) values (:id,:name,:dept,:role,:from,:to,:Reason,:AlteredFaculty,:LeaveType)';
             $query = $dbh->prepare($ins);
             $query->execute(array(':id' => $id, ':name' => $name, ':dept' => $dept, ':role' => $role, ':from' => $from, ':to' => $to, ':Reason' => $Reason, ':AlteredFaculty' => $AlteredFaculty, ':LeaveType' =>  $LeaveType));
          }  
        ?>      
            <!-- /*$per1 = $_POST['per1'];
            $per2 = $_POST['per2'];
            $per3 = $_POST['per3'];
            $per4 = $_POST['per4'];
            $per5 = $_POST['per5'];
            $per6 = $_POST['per6'];
            $per7 = $_POST['per7'];
            $per8 = $_POST['per8'];*/

            $dept="";

              // Get Staff Deptartment
            $getdept = 'SELECT * FROM staffdetails WHERE StaffID = :id';
            $query = $dbh->prepare($getdept);
            $query->execute(array(':id' => $id));

            if($query->rowCount() > 0){
              $row = $query->fetch(PDO::FETCH_ASSOC);   
              $dept = $row['StaffDept'];
            }
             
            // Insert Staff Leave
            $ins='INSERT into staffleave (StaffID,StaffName,StaffDept,Role,FromDate,ToDate,Reason,AlteredFaculty,LeaveType) values (:id,:name,:dept,:role,:fro,:to,:Reason,:alter,:LeaveType)';
             $query = $dbh->prepare($ins);
             $query->execute(array(':id' => $id, ':name' => $name, ':dept' => $dept, ':role' => $role, ':fro' => $from, ':to' => $to, ':Reason' => $Reason,':alert' => $alter, ':LeaveType' =>  $LeaveType));
             /*sleep(1);
             $getId = 'SELECT * from staffleave where StaffID = :id and FromDate = :fro';
             $getIdProcess = $dbh->prepare($getId);
             $getIdProcess->execute(array(':id' => $id, ':fro' => $from));
             $rows = $getStaffStatusProcess->fetch(PDO::FETCH_ASSOC);
             $leaveRequestId = $rows['S.No.'];

             $insertAlterQuery = 'INSERT into facultyaltered (request_id,peroid1,peroid2,peroid3,peroid4,peroid5,peroid6,peroid7,peroid8) values (:lid,:per1,:per2,:per3,:per4,:per5,:per6,:per7,:per8)';
             $insertAlterProcess = $dbh->prepare($insertAlterQuery);
             $insertAlterProcess->execute(array(':lid'=>$leaveRequestId,':per1'=>$per1,':per2'=>$per2,':per3'=>$per3,':per4'=>$per4,':per5'=>$per5,':per6'=>$per6,':per7'=>$per7,':per8'=>$per8));*/
          }  
        ?> -->
      </div>
      <!-- Leave Form Ends -->


      <!-- Leave Requests Starts -->
      <div id="leave_request" class="tabcontent">
        <h3><center>Leave Requested From Students</center></h3>
        <div id = "stud_leave"></div>
        </div>
      <!-- Leave Requests Ends -->

      <!-- History Starts -->
      <div id="history" class="tabcontent">
        <center><h3>Your Leave History</h3></center>
          <?php
            require "config/Connection.php";

            $id = $_SESSION['sess_user_id'];

            $getStaffLeaveQuery = 'SELECT * FROM staffleave where StaffID = :id ORDER BY AppliedOn DESC';
            $getStaffLeaveProcess = $dbh->prepare($getStaffLeaveQuery);
            $getStaffLeaveProcess->execute(array(':id' => $id));

            if($getStaffLeaveProcess->rowCount() > 0){
              print "<table class='table table-striped table-hover'>
                      <thead>
                        <tr>
                          <th>S.No.</th>
                          <th>Staff ID</th>
                          <th>From Date</th>
                          <th>To Date</th>
                          <th>Reason</th>
                          <th>Applied On</th>
                          <th>Faculty Altered</th>
                          <th>Leave Type</th>
                          <th>HOD Approval</th>
                          <th>Principal Approval</th>
                        </tr>
                      </thead>
                      <tbody>";
                      $serial = 1; 

              while($row = $getStaffLeaveProcess->fetch(PDO::FETCH_ASSOC)) {
                print "<tr>";
                print "<td> ". $serial . "</td>";
                print "<td> ". $row["StaffID"] . "</td>";
                print "<td> ". $row["FromDate"]. "</td>";
                print "<td> ". $row["ToDate"]. "</td>";
                print "<td> ". $row["Reason"]. "</td>";
                print "<td> ". $row["AppliedOn"]. "</td>";
                print "<td> ". $row["AlteredFaculty"]. "</td>";
                print "<td> ". $row["LeaveType"]. "</td>";
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

          $getStaffStatusQuery = 'SELECT * FROM staffleave where StaffID = :id ORDER BY AppliedOn DESC LIMIT 1';
          $getStaffStatusProcess = $dbh->prepare($getStaffStatusQuery);
          $getStaffStatusProcess->execute(array(':id' => $id));

          if($getStaffStatusProcess->rowCount() > 0){
            print "<table class='table table-bordered'>
                    <thead>
                      <tr>
                        <th>S.No.</th>
                        <th>Staff ID</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Reason</th>
                        <th>Applied On</th>
                        <th>Faculty Altered</th>
                        <th>Leave Type</th>
                        <th>HOD Approval</th>
                        <th>Principal Approval</th>
                      </tr>
                    </thead>
                    <tbody>";
                    $serial = 1; 

            while($row = $getStaffStatusProcess->fetch(PDO::FETCH_ASSOC)) {
              print "<tr class='info'>";
              print "<td> ". $serial . "</td>";
              print "<td> ". $row["StaffID"] . "</td>";
              print "<td> ". $row["FromDate"]. "</td>";
              print "<td> ". $row["ToDate"]. "</td>";
              print "<td> ". $row["Reason"]. "</td>";
              print "<td> ". $row["AppliedOn"]. "</td>";
              print "<td> ". $row["AlteredFaculty"]. "</td>";
              print "<td> ". $row["LeaveType"]. "</td>";
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
      <!-- Status Ends -->

      <!-- Report Starts -->
      <div id="report" class="tabcontent">
        <center><h3>Get Student Leave Report</h3></center>
        <div class="row">
          <div class="col-md-6">
            <center><h4>Report by Period</h4></center>
            <form action="">
              <div class="form-group">
                <label for="from">From :</label>
                <input type="date" class="form-control" id="fromDate">
              </div>
              <div class="form-group">
                <label for="to">To:</label>
                <input type="date" class="form-control" id="toDate">
              </div>
              <input type='hidden' id="staffId" name="staffId" value= <?php echo $_SESSION['sess_user_id']; ?> >
              <center><button type="button" class="btn btn-default" onclick="showReportByDate()">Search</button></center>
            </form>
          </div>
          <div class="col-md-6">
            <center><h4>Report by Student</h4></center>
            <form action="">
              <div class="form-group">
                <label for="Student Name">Select Student:</label>
                <select class="form-control" id="StudName">
                  <?php
                    require "config/Connection.php";

                    $id = $_SESSION['sess_user_id'];

                    // Get Staff Department
                    $getStaffStudentQuery = 'SELECT * FROM staffdetails WHERE StaffID = :id';
                    $getStaffStudentProcess = $dbh->prepare($getStaffStudentQuery);
                    $getStaffStudentProcess->execute(array(':id' => $id));

                    if($getStaffStudentProcess->rowCount() > 0){
                      $row = $getStaffStudentProcess->fetch(PDO::FETCH_ASSOC);
                      $dept = 
                      $fro = $row['from_stud'];
                      $to = $row['to_stud'];
                    }

                    // Get Students Assingned To Staff
                    $getStudentQuery = 'SELECT * FROM studentdetails where StudentID >= :fro and StudentID <= :to ORDER BY StudentID';
                    $getStudentProcess = $dbh->prepare($getStudentQuery);
                    $getStudentProcess->execute(array(':fro' => $fro, ':to' => $to ));
                    
                    if($getStudentProcess->rowCount() > 0){
                
                      while($row = $getStudentProcess->fetch(PDO::FETCH_ASSOC)) {
                        print "<option value=". $row["StudentID"] .">". $row["StudentName"] ."</option> ";
                      }
                    }else{
                      print "No Record Found..!!!! ";
                    }

                  ?>
                </select>
              </div>
              <center><button type="button" class="btn btn-default" onclick="showReportByName()">Search</button></center>
            </form>  
          </div>
        </div>  
        <br><center><button type="button" id="download" class="btn btn-default" onclick="studentReport()">Download Report</button></center><br>
        <div class="container" id="studentReport"></div>
      </div>
      <!-- Report Ends -->

    </div>

    <!-- Main Block Ends -->

    <script>
      $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip();   
      });
      
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

      // Report Script
      function showReportByDate() {
        var from = document.getElementById("fromDate").value;
        var to = document.getElementById("toDate").value;
        var id = document.getElementById("staffId").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("studentReport").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Report/StudentReportByPeriod.php?from=" + from+"&to=" + to+"&id="+id+"&Role=Staff", true);
        xmlhttp.send();
      }

      function showReportByName() {
        var studID = document.getElementById("StudName").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("studentReport").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Report/StudentReportByID.php?id=" + studID, true);
        xmlhttp.send();
      }

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

      StudentLeave();
      getStaff();

      setInterval(function(){  
        StudentLeave();
        Notification();
      }, 5000);

      function StudentLeave(){
        $.post('Controllers/Leave.php?action=getStudentLeave&user=Mentor', function(response){
          $('#stud_leave').html(response);
        });
      }

      function StudentLeaveApprove(leaveID) {
        var leaveid = leaveID;
        $.post('Controllers/Leave.php?action=updateStudentLeave&user=Mentor&lvlstatus=Approved&leaveid='+leaveid, function(response){
          $('#stud_leave_status').html(response);
        });
      }

      function StudentLeaveReject(leaveID) {
        var leaveid = leaveID;
        $.post('Controllers/Leave.php?action=updateStudentLeave&user=Mentor&lvlstatus=Rejected&leaveid='+leaveid, function(response){
        });
      }

      Notification();

      function Notification() {
        $.post('Controllers/Leave.php?action=getNotification&user=Mentor', function(response){
          $('#notification').html(response);
        });
      }

      function getStaff() {
        $.post('Controllers/staff.php?action=getStaff', function(response){
          $('#per1').html(response);
          $('#per2').html(response);
          $('#per3').html(response);
          $('#per4').html(response);
          $('#per5').html(response);
          $('#per6').html(response);
          $('#per7').html(response);
          $('#per8').html(response);
        });
      }

    </script>

  </body>
</html>


