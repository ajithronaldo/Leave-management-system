<?php 
  session_start();
  $id = isset($_SESSION['sess_user_id']);
  $role = isset($_SESSION['sess_userrole']);
  $name = isset($_SESSION['sess_username']);
  if(!isset($_SESSION['sess_username'])){
    header('Location: index.php?err=2');
  }
  if($_SESSION['sess_userrole'] != "Principal"){
    header('Location: index.php?err=2');
  }
  include('api/way2sms-api.php');
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
          <ul class="nav navbar-nav navbar-right" id ="notification">
            
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
      <button class="tablink" onclick="openPage('student_leave', this)"  id="defaultOpen">Student Request</button>
      <button class="tablink" onclick="openPage('staff_leave', this)">Staff Requests</button>
      <button class="tablink" onclick="openPage('hod_leave', this)" >HOD Requests</button>
      <button class="tablink" onclick="openPage('student_report', this)">Student Leave Report</button>
      <button class="tablink" onclick="openPage('staff_report', this)">Staff Leave Report</button>
      </div>

      <!-- Leave Request from Student Starts -->
      <div id="student_leave" class="tabcontent">
        <h3><center>Leave Request From Students</center></h3>
        <div id = "stud_leave"></div>

      </div>
      <!-- Leave Request from Student Ends -->

      <!-- Leave Request from Staff Starts -->
      <div id="staff_leave" class="tabcontent">
        <h3><center>Leave Requested From Staffs</center></h3>
        <div id=staffleave></div>

      </div>
      <!-- Leave Request from Staff Ends -->

      <!-- Leave Request from HOD Starts -->
      <div id="hod_leave" class="tabcontent">
        <center><h3>Leave Request from HOD</h3></center>
        <div id="showHodleave"></div>
      </div>
      <!-- Leave Request from HOD Ends -->

      <!-- Student Report Starts -->
      <div id="student_report" class="tabcontent">
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
                <label for="Student Dept">Select Department:</label>
                <select class="form-control" id="StudDept" onchange="showStudent(this.value)">
                  <option value="">Select a Department</option>
                  <option value="IT">B.E. IT</option>
                  <option value="CSE">B.E. CSE</option>
                  <option value="ECE">B.E. ECE</option>
                </select>
                <label for="Student Name">Select Student:</label>
                <select class="form-control" id="StudName">
                  
                </select>
              </div>
              <center><button type="button" class="btn btn-default" onclick="showReportByName()">Search</button></center>
            </form>  
          </div>
        </div>  
        <br><center><button type="button" id="download" class="btn btn-default" onclick="studentReport()">Download Report</button></center><br>
        <div class="container" id="studentReport"></div>
      </div>
      <!-- Student Report Ends -->

      <!-- Staff Report Starts -->
      <div id="staff_report" class="tabcontent">
        <center><h3>Get Staff Leave Report</h3></center>
        <div class="row">
          <div class="col-md-6">
            <center><h4>Report by Period</h4></center>
            <form action="">
              <div class="form-group">
                <label for="from">From :</label>
                <input type="date" class="form-control" id="staffFromDate">
              </div>
              <div class="form-group">
                <label for="to">To:</label>
                <input type="date" class="form-control" id="staffToDate">
              </div>
              <input type='hidden' id="staffId" name="staffId" value= <?php echo $_SESSION['sess_user_id']; ?> >
              <center><button type="button" class="btn btn-default" onclick="staffReportByDate()">Search</button></center>
            </form>
          </div>
          <div class="col-md-6">
            <center><h4>Report by Staff</h4></center>
            <form action="">
              <div class="form-group">
                <label for="Student Dept">Select Department:</label>
                <select class="form-control" id="StaffDept" onchange="showStaff(this.value)">
                  <option value="">Select a Department</option>
                  <option value="IT">B.E. IT</option>
                  <option value="CSE">B.E. CSE</option>
                  <option value="ECE">B.E. ECE</option>
                </select>
                <label for="Student Name">Select Student:</label>
                <select class="form-control" id="StaffName">
                  
                </select>
              </div>
              <center><button type="button" class="btn btn-default" onclick="staffReportByName()">Search</button></center>
            </form>  
          </div>
        </div>  
        <br><center><button type="button" id="download" class="btn btn-default" onclick="staffReport()">Download Report</button></center><br>
        <div class="container" id="staffReport"></div>
      </div>
      <!-- Staff Report Ends -->

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

      function showStudent(str) {
        if (str=="") {
          document.getElementById("StudName").innerHTML="";
          return;
        }
        if (window.XMLHttpRequest) {
          xmlhttp=new XMLHttpRequest();
        }else {
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange=function(){
          if (this.readyState==4 && this.status==200){
            document.getElementById("StudName").innerHTML=this.responseText;
          }
        }
        xmlhttp.open("GET","Report/getNameByDept.php?dept="+str+"&role=Student",true);
        xmlhttp.send();
      }

      function showStaff(str) {
        if (str=="") {
          document.getElementById("StaffName").innerHTML="";
          return;
        }
        if (window.XMLHttpRequest) {
          xmlhttp=new XMLHttpRequest();
        }else {
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange=function(){
          if (this.readyState==4 && this.status==200){
            document.getElementById("StaffName").innerHTML=this.responseText;
          }
        }
        xmlhttp.open("GET","Report/getNameByDept.php?dept="+str+"&role=allStaff",true);
        xmlhttp.send();
      }

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
        xmlhttp.open("GET", "Report/StudentReportByPeriod.php?from=" + from+"&to=" + to+"&id="+id+"&Role=Principal", true);
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

      function staffReportByDate() {
        var from = document.getElementById("staffFromDate").value;
        var to = document.getElementById("staffToDate").value;
        var id = document.getElementById("staffId").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("staffReport").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Report/StaffReportByPeriod.php?from=" + from+"&to=" + to+"&id="+id+"&Role=Principal", true);
        xmlhttp.send();
      }

      function staffReportByName() {
        var staffID = document.getElementById("StaffName").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("staffReport").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Report/StaffReportByID.php?id=" + staffID, true);
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

      setInterval(function(){  
        StudentLeave();
        StaffLeave();
        HODLeave();
        Notification();
      }, 10000);

      function StudentLeave(){
        $.post('Controllers/Leave.php?action=getStudentLeave&user=Principal', function(response){
          $('#stud_leave').html(response);
        });
      }

      function StudentLeaveApprove(leaveID) {
        var leaveid = leaveID;
        $.post('Controllers/Leave.php?action=updateStudentLeave&user=Principal&lvlstatus=Approved&leaveid='+leaveid, function(response){
          $('#stud_leave_status').html(response);
        });
      }

      function StudentLeaveReject(leaveID) {
        var leaveid = leaveID;
        $.post('Controllers/Leave.php?action=updateStudentLeave&user=Principal&lvlstatus=Rejected&leaveid='+leaveid, function(response){
        });
      }

      StaffLeave();

      function StaffLeave(){
        $.post('Controllers/Leave.php?action=getStaffLeave&user=Principal', function(response){
          $('#staffleave').html(response);
        });
      }

      function StaffLeaveApprove(leaveID) {
        var leaveid = leaveID;
        $.post('Controllers/Leave.php?action=updateStaffLeave&user=Principal&lvlstatus=Approved&leaveid='+leaveid, function(response){
          $('#stud_leave_status').html(response);
        });
      }

      function StaffLeaveReject(leaveID) {
        var leaveid = leaveID;
        $.post('Controllers/Leave.php?action=updateStaffLeave&user=Principal&lvlstatus=Rejected&leaveid='+leaveid, function(response){
        });
      }

      HODLeave();

      function HODLeave(){
        $.post('Controllers/Leave.php?action=getHODLeave', function(response){
          $('#showHodleave').html(response);
        });
      }

      Notification();

      function Notification() {
        $.post('Controllers/Leave.php?action=getNotification&user=Principal', function(response){
          $('#notification').html(response);
        });
      }


    </script>

  </body>
</html>


