<!--
Glen Anderson
UMass Lowell Computer Science
DB II Website
update_process_add.php
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
                            <li class="active"><a href="update.php">Update Schedule<span class="sr-only">(current)</span></a></li>
                            <li><a href="delete.php">Delete Student</a></li>
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
            $course_in = trim($_POST['course_in']);
            $term_in = trim($_POST['term_in']);
            $section_in = trim($_POST['section_in']);
            $year_in = trim($_POST['year_in']);

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
                            print " &nbsp; <div class=\"label label-primary\">Course#: $course_in </div><br>";
                            print " &nbsp; <div class=\"label label-primary\">Term: $term_in</div><br>";
                            print " &nbsp; <div class=\"label label-primary\">Section#: $section_in </div><br>";
                            print " &nbsp; <div class=\"label label-primary\">Year: $year_in </div><br>";

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
                                //}

                            }
                            if ( (empty($section_in)) || (!is_numeric($section_in)) ){
                                echo '<div class=" label label-danger">An invalid section was entered</div><br>';
                                mysqli_close($myconnection);
                                exit;
                            }
                            if ( (empty($year_in)) || (!is_numeric($year_in)) ){
                                echo '<div class=" label label-danger">An invalid year was entered</div><br>';
                                mysqli_close($myconnection);
                                exit;
                            }

                            $query = "SELECT name
                                      FROM students
                                      WHERE students.SID = $id_in";

                            $result = mysqli_query($myconnection, $query)or die('Query failed: ' . mysql_error()) ;
                            $row = mysqli_fetch_assoc($result);
                            $name = $row['name'];

                            if($name == $name_in){
                                echo "<div class=\" label label-success\"> $name was found</div><br>";
                            } else {
                                echo '<div class=" label label-danger">Name and ID do not match</div><br>';
                                mysqli_close($myconnection);
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
                        Course Enrollment
                    </div>
                    <div class="panel-body">

                        <?php

                        $query2 = "SELECT courses.CID, section.SecID, section.yearID, section.semesterID, courses.name
                                   FROM courses, section
                                   WHERE courses.CID = $course_in AND
                                         courses.CID = section.CID AND
                                         section.secID = $section_in AND
                                         section.yearID = $year_in AND
                                         section.semesterID = '$term_in'";

                        $result2 = mysqli_query($myconnection, $query2)or die('Query failed: ' . mysql_error());
                        
                        if (mysqli_num_rows($result2) < 1){
                            echo '<p class="text-danger">Class could not be found.</p>';
                            mysqli_free_result($result2);
                            mysqli_close($myconnection);
                            exit;
                        }
                        
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            
                            //echo "<br>----------------------<br>";
                            $courseID_in = $row2['CID'];
                            //echo "Course ID: " . $row2['CID'];
                            //echo "<br>Section: " . $row2['SecID'];
                            //echo "<br>Year: " . $row2['yearID'];
                            //echo "<br>Term: " . $row2['semesterID'];
                            //echo "<br>Course id from var: $courseID_in";  
                        }
                        mysqli_free_result($result2);
                        /////////////////
                        //check pre-reqs.
                        /////////////////
                        $query2_pre = "SELECT PCID FROM prerequisite WHERE CID = $courseID_in";
                        $result2_pre = mysqli_query($myconnection, $query2_pre);
                        $numPreReqs = mysqli_num_rows($result2_pre);
                        
                        if ($numPreReqs > 0){
                            echo "<br><span class='text-info'>Course has a prerequesite: </span>";
                            
                            //get course ID of prereq
                            $row2_pre = mysqli_fetch_assoc($result2_pre);
                            $preReq = $row2_pre['PCID'];
                            echo " &nbsp; $preReq ";
                            
                            //get course name of prereq
                            $query2_reqName = "SELECT name FROM courses WHERE CID = $preReq";
                            $result2_reqName = mysqli_query($myconnection, $query2_reqName);
                            $row2_reqName = mysqli_fetch_assoc($result2_reqName);
                            $req_name = $row2_reqName['name'];
                            echo " &nbsp; &nbsp;($req_name) <br>";
                            
                            mysqli_free_result($result2_reqName);
                            
                            //check if SID and prereq course id are in enrollment table
                            $query2_req = "SELECT * FROM enrollment WHERE CID = $preReq AND SID = $id_in";
                            $result2_req = mysqli_query($myconnection, $query2_req);
                            $numConds= mysqli_num_rows($result2_req);
                            if ($numConds > 0){
                                echo "<br>Required course was taken.<br>";
                                mysqli_free_result($result2_req);
                            }else {
                                echo "<br><p class = 'text-danger'>Required course was not taken.</p>";
                                mysqli_free_result($result2_req);
                                mysqli_close($myconnection);
                                exit;
                            }  
                        }
                        
                        mysqli_free_result($result2_pre);
                        ////////////////
                        // add class
                        ///////////////
                        $query3 = "INSERT INTO enrollment VALUES ('$id_in', '$courseID_in', '$section_in', '$year_in', '$term_in', '')";
                        $result3 = mysqli_query($myconnection, $query3); //or die('Query failed: ' . mysql_error());
                        if ($result3){
                            echo "<p class='text-success'><br>Class added successfully for $name_in ($id_in).  </p>";
                            //echo "Class Name: $course_in <br>";
                            echo "Class ID: $courseID_in <br>";
                            echo "Section: $section_in <br>";
                            echo "Semester: $term_in <br>";
                            echo "Year: $year_in <br>";
                        } else{
                            echo '<p class="text-danger"><br>Error adding class. Class may already have been added.</p>';
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
