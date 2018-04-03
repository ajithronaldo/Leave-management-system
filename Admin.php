<?php 
    session_start();
    $id = isset($_SESSION['sess_user_id']);
    $role = isset($_SESSION['sess_userrole']);
    $name = isset($_SESSION['sess_username']);
    if(!isset($_SESSION['sess_username'])){
      header('Location: index.php?err=2');
    }
    if($_SESSION['sess_userrole'] != "Admin"){
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
            <li><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#passwordModal">Reset Password</button></li>
            <li><a href="#"><?php echo $_SESSION['sess_username'];?></a></li>
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
            <h4 class="modal-title">Reset Password</h4>
          </div>
          <div class="modal-body">
            <form action="">
              <div class="form-group">
                <label for="email">Student/Staff ID:</label>
                <input type="text" class="form-control" id="pwdid" placeholder="Enter Student/Staff ID">
              </div>
              <div class="form-group">
                <label for="pwd">Conform Password:</label>
                <input type="text" class="form-control" id="uppwd" value="Kgkite@123">
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
          <button class="tablink" onclick="openPage('student', this)"  id="defaultOpen">Student</button>
          <button class="tablink" onclick="openPage('staff', this)" >Staff</button>
      </div>

      <!-- Student Admin Panel Starts -->
      <div id="student" class="tabcontent">
        <h3><center>Add / Modify / Delete Student</center></h3>
        <div class="form-group">
          <label for="studentOption">Select Menu:</label>
          <select class="form-control" id="studentOption" >
            <option value="">Select Operation</option>
            <option value="add">Add Student</option>
            <option value="update">Update Student</option>
            <option value="delete">Delete Student</option>
          </select>
        </div>
        <div id="add" class="menu" style="display:none">
          <div class="col-md-offset-3 col-md-6">
            <form class="form-horizontal" action="">
              <div class="form-group">
                <label class="control-label col-sm-2" for="userid">Roll No:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sdid" placeholder="Enter Roll No." name="sdid">
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-2" for="sname">Name:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sdname" placeholder="Enter Name" name="sdname">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="sgen">Gender:</label>
                <div class="col-sm-10">
                  <select class="form-control" id="sdgen">
                    <option value ="">Select a Gender</option>
                    <option value ="Male">Male</option>
                    <option value ="Female">Female</option>
                  </select>
                 </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-2" for="sdept">Department:</label>
                <div class="col-sm-10">
                  <select class="form-control" id="sddept">
                    <option value ="">Select a Department</option>
                    <option value ="IT">B.E. IT</option>
                    <option value ="CSE">B.E. CSE</option>
                    <option value ="ECE">B.E. ECE</option>
                  </select>
                 </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Email:</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="sdemail" placeholder="Enter email" name="email">
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-2" for="sphone">Phone:</label>
                <div class="col-sm-10">
                  <input type="sphone" class="form-control" id="sdphone" placeholder="Enter Phone Number" name="sphone">
                </div>
              </div>
            
              <div class="form-group">
                <label class="control-label col-sm-2" for="stype">Type:</label>
                <div class="col-sm-10">
                  <select class="form-control" id="sdtype">
                    <option value ="">Select a Student Type</option>
                    <option value ="Dayscholar">Day Scholar</option>
                    <option value ="Hosteller">Hosteller</option>
                  </select>
                 </div>
              </div>

              <div class="form-group">        
                <div class="col-sm-offset-2 col-md-4">
                  <button type="button" class="btn btn-default" onclick="addStudent()">Add</button>
                </div>
              </div>
            </form>
            <div class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Status : </strong><div id="addStudentStatus"></div>
            </div>
          </div>
        </div>

        <div id="update" class="menu" style="display:none">
          <div class="col-md-offset-3 col-md-6">
            <form class="form-horizontal" action="">
              <div class="form-group">
                <label class="control-label col-sm-2" for="sdupid">Roll No:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sdupid" placeholder="Enter Roll No" name="sdupid">
                </div>
              </div>
              
              <div id="replaceUpdate">
                <div class="form-group">        
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-default" onclick="searchStudent()">Search</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div id="delete" class="menu" style="display:none">
          <div class="col-md-offset-3 col-md-6">
            <form class="form-horizontal" action="">
              <div class="form-group">
                <label class="control-label col-sm-2" for="sddlid">Roll No:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sddlid" placeholder="Enter student Roll no" name="sddlid">
                </div>
              </div>
              
              <div id="replaceDelete">
                <div class="form-group">        
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-default" onclick="searchForStudent()">Search</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
                
      </div>
      <!-- Student Admin Panel Ends -->

      <!-- Staff Admin Panel Starts -->
      <div id="staff" class="tabcontent">
        <center><h3>Add / Modify / Delete Staff</h3></center>

        <div class="form-group">
          <label for="staffOption">Select Menu:</label>
          <select class="form-control" id="staffOption" >
            <option value="">Select Operation</option>
            <option value="addStaffID">Add Staff</option>
            <option value="updateStaffID">Update Staff</option>
            <option value="deleteStaffID">Delete Staff</option>
          </select>
        </div>
        <div id="addStaffID" class="menu" style="display:none">
          <div class="col-md-offset-3 col-md-6">
            <form class="form-horizontal" action="">
              <div class="form-group">
                <label class="control-label col-sm-2" for="userid">Employee ID:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sfid" placeholder="Enter Employee ID." name="sfid">
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-2" for="sname">Name:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sfname" placeholder="Enter Name" name="sfname">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Email:</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="sfemail" placeholder="Enter email" name="email">
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-2" for="sphone">Phone:</label>
                <div class="col-sm-10">
                  <input type="sphone" class="form-control" id="sfphone" placeholder="Enter Phone Number" name="sphone">
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-2" for="sfrole">Designation:</label>
                <div class="col-sm-10">
                  <select class="form-control" id="sfrole" >
                    <option value ="">Select a Designation</option>
                    <option value ="Mentor">Mentor</option>
                    <option value ="HOD">HOD</option>
                    <option value ="Principal">Principal</option>
                  </select>
                 </div>
              </div>

              <div id="getMenu">
                
              </div>              
            
              <div class="form-group">        
                <div class="col-sm-offset-2 col-md-4">
                  <button type="button" class="btn btn-default" onclick="addStaff()">Add</button>
                </div>
              </div>
            </form>
            <div class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Status : </strong><div id="addStaffStatus"></div>
            </div>
          </div>
        </div>

        <div id="updateStaffID" class="menu" style="display:none">
          <div class="col-md-offset-3 col-md-6">
            <form class="form-horizontal" action="">
              <div class="form-group">
                <label class="control-label col-sm-2" for="sfupid">Roll No:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sfupid" placeholder="Enter Staff Roll no" name="sfupid">
                </div>
              </div>
              
              <div id="replaceUpdateStaff">
                <div class="form-group">        
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-default" onclick="searchStaff()">Search</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div id="deleteStaffID" class="menu" style="display:none">
          <div class="col-md-offset-3 col-md-6">
            <form class="form-horizontal" action="">
              <div class="form-group">
                <label class="control-label col-sm-2" for="sfdlid">Employee No:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sfdlid" placeholder="Enter Staff no" name="sfdlid">
                </div>
              </div>
              
              <div id="replaceDeleteStaff">
                <div class="form-group">        
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-default" onclick="searchForStaff()">Search</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Staff Admin Panel Ends -->

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

      $(function() {
        $('#studentOption').change(function(){
            $('.menu').hide();
            $('#' + $(this).val()).show();
        });
      });

      function addStudent() {
        var sdid = document.getElementById("sdid").value;
        var sdname = document.getElementById("sdname").value;
        var sddept = document.getElementById("sddept").value;
        var sdemail = document.getElementById("sdemail").value;
        var sdphone = document.getElementById("sdphone").value;
        var sdgen = document.getElementById("sdgen").value;
        var sdtype = document.getElementById("sdtype").value;
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("addStudentStatus").innerHTML = this.responseText;
                $("#sdid").val('');
                $("#sdname").val('');
                $("#sddept").val('');
                $("#sdemail").val('');
                $("#sdphone").val('');
                $("#sdgen").val('');
                $("#sdtype").val('');
            }
        };
        xmlhttp.open("GET", "Controllers/StudentDB.php?status=add&sdid=" +sdid+"&sdname="+sdname+"&sddept="+sddept+"&sdemail="+sdemail+"&sdphone="+sdphone+"&sdgen="+sdgen+"&sdtype="+sdtype, true);
        xmlhttp.send();
      }

      function searchStudent() {
        var sdid = document.getElementById("sdupid").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("replaceUpdate").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Controllers/StudentDB.php?status=getStudent&sdid=" +sdid, true);
        xmlhttp.send();
      }

      function updateStudent() {
        var sdupid = document.getElementById("sdupid").value;
        var sdupname = document.getElementById("sdupname").value;
        var sdupemail = document.getElementById("sdupemail").value;
        var sdupphone = document.getElementById("sdupphone").value;
        var sdupgen = document.getElementById("sdupgen").value;
        var sduptype = document.getElementById("sduptype").value;
        var sdupdept = document.getElementById("sdupdept").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("updateStudentStatus").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Controllers/StudentDB.php?status=update&sdid=" +sdupid+"&sdname="+sdupname+"&sdemail="+sdupemail+"&sdphone="+sdupphone+"&sdgen="+sdupgen+"&sdtype="+sduptype+"&sddept="+sdupdept, true);
        xmlhttp.send();
      }


      function searchForStudent() {
        var sdid = document.getElementById("sddlid").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("replaceDelete").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Controllers/StudentDB.php?status=getForStudent&sdid=" +sdid, true);
        xmlhttp.send();
      }

      function deleteStudent() {
        var sdid = document.getElementById("sddlid").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("deleteStudentStatus").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Controllers/StudentDB.php?status=deleteStudent&sdid=" +sdid, true);
        xmlhttp.send();
      }

      $(function() {
        $('#staffOption').change(function(){
            $('.menu').hide();
            $('#' + $(this).val()).show();
        });
      });


      $('#sfrole').on('change', function() {
        if( this.value  == 'Principal'){
          $('#getMenu').html("");
        }else if(this.value  == 'HOD'){
          $('#getMenu').html('<div class="form-group"><label class="control-label col-sm-2" for="sfept">Department:</label><div class="col-sm-10"><select class="form-control" id="sfdept"><option value ="">Select a Department</option><option value ="IT">B.E. IT</option><option value ="CSE">B.E. CSE</option><option value ="ECE">B.E. ECE</option></select></div></div>');
        }else if(this.value  == 'Mentor'){
          $('#getMenu').html('<div class="form-group"><label class="control-label col-sm-2" for="sfept">Department:</label><div class="col-sm-10"><select class="form-control" id="sfdept"><option value ="">Select a Department</option><option value ="IT">B.E. IT</option><option value ="CSE">B.E. CSE</option><option value ="ECE">B.E. ECE</option></select></div></div><div class="form-group"><label class="control-label col-sm-2" for="fromStud">From Student:</label><div class="col-sm-10"><input type="text" class="form-control" id="sffrom" placeholder="Enter From Student" name="sffrom"></div></div><div class="form-group"><label class="control-label col-sm-2" for="toStud">To Student:</label><div class="col-sm-10"><input type="text" class="form-control" id="sfto" placeholder="Enter To Student" name="sfto"></div></div>');
        }
      });

      function addStaff() {
        var sfid = document.getElementById("sfid").value;
        var sfname = document.getElementById("sfname").value;
        var sfemail = document.getElementById("sfemail").value;
        var sfphone = document.getElementById("sfphone").value;
        var sfrole = document.getElementById("sfrole").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("addStaffStatus").innerHTML = this.responseText;
                
            }
        };

        if(sfrole == 'Principal'){

          xmlhttp.open("GET", "Controllers/StaffDB.php?status=add&sfid=" +sfid+"&sfname="+sfname+"&sfemail="+sfemail+"&sfphone="+sfphone+"&sfrole="+sfrole, true);

        }else if(sfrole == 'HOD'){
          var sfdept = document.getElementById("sfdept").value;

          xmlhttp.open("GET", "Controllers/StaffDB.php?status=add&sfid=" +sfid+"&sfname="+sfname+"&sfdept="+sfdept+"&sfemail="+sfemail+"&sfphone="+sfphone+"&sfrole="+sfrole, true);

        }else if(sfrole == 'Mentor'){
          var sfdept = document.getElementById("sfdept").value;
          var sffrom = document.getElementById("sffrom").value;
          var sfto = document.getElementById("sfto").value;

          xmlhttp.open("GET", "Controllers/StaffDB.php?status=add&sfid=" +sfid+"&sfname="+sfname+"&sfdept="+sfdept+"&sfemail="+sfemail+"&sfphone="+sfphone+"&sfrole="+sfrole+"&sffrom="+sffrom+"&sfto="+sfto, true);
        }

        xmlhttp.send();
      }

      function searchStaff() {
        var sfid = document.getElementById("sfupid").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("replaceUpdateStaff").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Controllers/StaffDB.php?status=getStaff&sfid=" +sfid, true);
        xmlhttp.send();
      }

      function updateStaff() {
        var sfupid = document.getElementById("sfupid").value;
        var sfupname = document.getElementById("sfupname").value;
        var sfupemail = document.getElementById("sfupemail").value;
        var sfupphone = document.getElementById("sfupphone").value;
        var sfupfrom = document.getElementById("sfupfrom").value;
        var sfupto = document.getElementById("sfupto").value;
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("updateStaffStatus").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Controllers/StaffDB.php?status=update&sfid=" +sfupid+"&sfname="+sfupname+"&sfemail="+sfupemail+"&sfphone="+sfupphone+"&sffrom="+sfupfrom+"&sfto="+sfupto, true);
        xmlhttp.send();
      }

      function searchForStaff() {
        var sfid = document.getElementById("sfdlid").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("replaceDeleteStaff").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Controllers/StaffDB.php?status=getForStaff&sfid=" +sfid, true);
        xmlhttp.send();
      }

      function deleteStaff() {
        var sfid = document.getElementById("sfdlid").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("deleteStaffStatus").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "Controllers/StaffDB.php?status=deleteStaff&sfid=" +sfid, true);
        xmlhttp.send();
      }

      function updatePassword() {
        var id = document.getElementById("pwdid").value;
        var password = document.getElementById("uppwd").value;

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


