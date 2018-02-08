<!--
Glen Anderson
UMass Lowell Computer Science
DB II Website
delete_process.php
-->
<!DOCTYPE html>
<html>
    <head>

        <!--cdn Sandstone bootstrap theme -->
        <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/sandstone/bootstrap.min.css" rel="stylesheet" integrity="sha256-oqtj+Pkh1c3dgdH6V9qoS7qwhOy2UZfyVK0qGLa9dCc= sha512-izanB/WZ07hzSPmLkdq82m5xS7EH/qDMgl5aWR37EII+rJOi5c6ouJ3PYnrw6K+DWQcnMZ+nO1NqDr6SBKLBDg==" crossorigin="anonymous">

        <!-- my css file and a cdn google font -->
        <style>@import url(./styles/my_css.css) </style>
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,200italic,300,300italic,600,400italic,600italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>

        <!-- favicon icon generated from http://www.xiconeditor.com -->
        <link rel="icon" type="image/vnd.microsoft.icon" href="./archives/sis.ico">
        
        <title>Glen Anderson</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>

    <body>
        <div class ="container-fluid">
            <header class="text-center">
                <h1 > <strong> Student Information System</strong> </h1>
                <h3>Glen Anderson</h3>
                <hr class="hr hr-primary">
            </header>

            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="./index.html"> &nbsp; SIS &nbsp; &nbsp;  </a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="view.html">View Student Info</a></li>
                            <li><a href="new.html">New Student</a></li>
                            <li><a href="update.php">Update Schedule</a></li>
                            <li  class="active"><a href="delete.php">Delete Student <span class="sr-only">(current)</span></a></li>
                            <li><a href="grade.php">Edit Grade</a></li>
                            <li><a href="graduate.php">Graduate</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h3><strong>Results</strong></h3>

            <?php
            print "<p> Information processed at " . date('H:i, jS F Y') . "</p>";

            $id_in = trim($_POST['id_in']);
            $name_in = trim($_POST['name_in']);
            //$career_in = trim($_POST['career_in']);
            // $degree_in = trim($_POST['degree_in']);
            // $major_in = trim($_POST['major_in']);

            ?>

            <span class="col-lg-2">
                <div class="panel panel-primary ">

                    <div class="panel-heading" style="font-size: 1.3em">
                        Status
                    </div>

                    <div class="panel-body ">
                        <div class="panel-content">
                            <?php
                            print "<h5 class=\"text-primary\">Values Entered</h5>";
                            print " &nbsp; <div class=\"label label-primary\">Name: $name_in</div><br>";
                            print " &nbsp; <div class=\"label label-primary\">ID#: $id_in </div><br>";
                            //print " &nbsp; <div class=\"label label-primary\">Career: $career_in </div><br>";
                            //print " &nbsp; <div class=\"label label-primary\">Degree: $degree_in</div><br>";
                            // print " &nbsp; <div class=\"label label-primary\">Major: $major_in </div><br>";

                            @ $myconnection = mysqli_connect('localhost', 'root', '', 'info');

                            if (mysqli_connect_errno()) {
                                echo '<div class="col-lg-3">';
                                echo '<div class = "alert alert-dismissible alert-danger">';
                                echo '<button type="button" class="close" data-dismiss="alert"></button>';
                                echo '<strong>Oops!</strong> Couldn\'t connect to database';
                                echo '</div>';
                                echo '</div>';
                                exit;
                            } else {

                                echo "<br><h5 class=\"text-success\">Return Status</h5>";
                                echo '<div class=" label label-success">Connected to database</div><br>';

                                //if (get_magic_quotes_gpc()) {
                                //    echo '<div class=" label label-success">GPC Magic is ON</div>';
                                //} else {
                                //    echo '<div class=" label label-success">GPC Magic is OFF</div><br>';
                                // }
                            }

                            //Make sure input ID # and name match a student in the database
                            $query = "SELECT name
                                      FROM students
                                      WHERE students.SID = $id_in";

                            $result = mysqli_query($myconnection, $query)or die('Query failed: ' . mysql_error()) ;

                            $row = mysqli_fetch_assoc($result);
                            $name = $row['name'];

                            if($name == $name_in){
                                echo "<div class=\" label label-success\"> $name was found</div><br>";
                            } else {
                                echo '<div class=" label label-danger">Student could not be found</div><br>';
                                exit;
                            }

                            mysqli_free_result($result);
                            ?>

                        </div>

                    </div>
                </div>
            </span>

            <span class = "col-lg-7">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="font-size: 1.3em">
                        Student Removal
                    </div>
                    <div class="panel-body">

                        <?php

                        //Delete the student
                        $query_delStudent =" DELETE 
                                             FROM students
                                             WHERE students.SID = $id_in AND
                                                  students.name = '$name_in' ";
                        
                        $result2 = mysqli_query($myconnection, $query_delStudent) or die('Query failed: ' . mysql_error()) ;
                        
                        //Check to make sure student was deleted
                        if (( !$result2) || (mysqli_affected_rows($myconnection) < 1 )){
                            echo '<p class="text-danger">Student could not be deleted.</p>';
                        } else {
                            echo '<p class="text-success">Student was deleted successfully.</p>';
                        }
                        
                        //Delete all the classes that the deleted student was enrolled in
                        $query_delClasses = "DELETE
                                            FROM enrollment
                                            WHERE SID = $id_in";
                        $result3 = mysqli_query($myconnection, $query_delClasses) or die('Query failed: ' . mysql_error()) ;
                        
                        //Check to make sure classes were deleted
                        if (( !$result3) || (mysqli_affected_rows($myconnection) < 1 )){
                            echo '<p class="text-danger">No classes were deleted.</p>';
                        } else {
                            echo '<p class="text-success">Classes deleted successfully.</p>';
                        }        
                        
                        mysqli_close($myconnection);
                        ?>
                        
                    </div>
                </div>
            </span>

        </div>
        <footer class = "navbar-fixed-bottom" style="text-align: center">Copyright 2016</footer>
    </body>
</html>
