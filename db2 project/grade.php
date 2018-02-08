<!--
Glen Anderson
UMass Lowell Computer Science
DB II Website
grade.php
-->
<!DOCTYPE html>
<html>
    <head>

        <!--jQuery -->
        <script   src="https://code.jquery.com/jquery-2.2.2.min.js"   integrity="sha256-36cp2Co+/62rEAAYHLmRCPIych47CvdM+uTBJwSzWjI="   crossorigin="anonymous"></script>
        
        <!--cdn Sandstone bootstrap theme -->
        <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/sandstone/bootstrap.min.css" rel="stylesheet" integrity="sha256-oqtj+Pkh1c3dgdH6V9qoS7qwhOy2UZfyVK0qGLa9dCc= sha512-izanB/WZ07hzSPmLkdq82m5xS7EH/qDMgl5aWR37EII+rJOi5c6ouJ3PYnrw6K+DWQcnMZ+nO1NqDr6SBKLBDg==" crossorigin="anonymous">

        <!-- my css file and a cdn google font -->
        <style>@import url(./styles/my_css.css) </style>
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,200italic,300,300italic,600,400italic,600italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>

        <!-- bootstrap -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        
        <!-- favicon icon generated from http://www.xiconeditor.com -->
        <link rel="icon" type="image/vnd.microsoft.icon" href="./archives/fav2.ico">
        
        <title>Glen Anderson</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>

    <body>

        <div class ="container-fluid" >

            <header class="text-center">     
                <h1 > <strong> Student Information System</strong> </h1>
                <h3>Glen Anderson</h3>
                <hr class="hr hr-primary">     
            </header>

            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="./index.html">  &nbsp; SIS &nbsp; &nbsp; </a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs1">
                        <ul class="nav navbar-nav">
                            <li><a href="view.html">View Student Info</a></li>
                            <li><a href="new.html">New Student</a></li>
                            <li><a href="update.php">Update Schedule</a></li>
                            <li> <a href="delete.php">Delete Student</a></li>
                            <li  class ="active"><a href="grade.php">Edit Grade<span class="sr-only">(current)</span></a></li>
                            <li><a href="graduate.php">Graduate</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h3><strong>Edit Grade For Class</strong></h3>

            <div class="col-lg-9">
                
                <h3>Form</h3>
                <form action ="grade_process.php" role = "form" method = "post">
                    <br>
                    
                    <!-- Student ID # form -->
                    <div class= "form-group col-lg-2">
                        <label>ID# </label>
                        <select name="id_in">
                            <?php
                            $myconnection = mysqli_connect('localhost', 'root', '', 'info');
                            $query = "Select SID FROM students";
                            $result = mysqli_query($myconnection, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $id = $row["SID"];
                                echo "<option>  $id  </option>";
                            }
                            mysqli_free_result($result);
                            ?>  
                        </select>
                    </div>

                    <!-- Student name form -->
                    <div class ="row">
                        <div class="form-group col-lg-3 ">
                            <label>Name</label>
                            <select name="name_in">
                                <?php
                                $query2 = "Select name FROM students";
                                $result2 = mysqli_query($myconnection, $query2);

                                while ($row = mysqli_fetch_assoc($result2)) {
                                    $id = $row["name"];
                                    echo "<option>  $id  </option>";
                                }
                                mysqli_free_result($result2)
                                ?>  
                            </select>
                        </div>
                    </div>
                    
                    <br>
                    
                    <!-- Course form -->
                    <div class="form-group col-lg-2 ">
                        <label>Course</label>
                        <select name="course_in">
                            <?php
                            $query3 = "SELECT DISTINCT name, CID FROM courses";
                            $result3 = mysqli_query($myconnection, $query3);

                            while ($row3 = mysqli_fetch_assoc($result3)) {
                                $course_opt = $row3["name"];
                                $courseID_opt = $row3["CID"];
                                echo "<option value='$courseID_opt'>  $course_opt  </option>";
                            }
                            mysqli_free_result($result3)
                            ?>  
                        </select>
                    </div>

                    <div class ='row'></div>

                    <!-- Year form -->
                    <div class="form-group col-lg-2 ">
                        <label>Year</label>
                        <input type="text" name="year_in" class="form-control" placeholder="Year" maxlength = "10">
                    </div>

                    <!-- Semester form -->
                    <div class="form-group col-lg-1 ">
                        <label>Semester</label><br>
                        <select name="term_in">
                            <option value='F'>Fall</option>
                            <option value='S'>Spring</option>
                        </select>
                    </div>

                    <!-- New grade form -->
                    <div class ="row">
                        <div class="form-group col-lg-3 ">
                            <label><h3>&nbsp; &nbsp; &nbsp; New Grade: &nbsp;</h3></label>
                            <select name="grade_in">
                                <option value='A+'> A+ </option>
                                <option value='A'> A </option>
                                <option value='A-'> A- </option>
                                <option value='B+'> B+ </option>
                                <option value='B'> B </option>
                                <option value='B-'> B- </option>
                                <option value='C+'> C+ </option>
                                <option value='C'> C </option>
                                <option value='C-'> C- </option>
                                <option value='D+'> D+ </option>
                                <option value='D'> D </option>
                                <option value='D-'> D- </option>
                                <option value='F'> F </option>
                                <option value=' '>  </option>
                            </select>

                        </div>
                    </div>

                    <div class="row"></div>

                    <br>
                    <div class="col-lg-3">
                        <input type="submit" name = "Submit" class="btn btn-primary " value = " Submit Info ">
                    </div>

                </form> 
            </div>
        </div>

        <footer class = "navbar-fixed-bottom" style="text-align: center">Copyright 2016</footer>
    </body>    
</html>