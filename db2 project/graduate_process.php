<!--
Glen Anderson
UMass Lowell Computer Science
DB II Website
graduate_process.php
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
                            <li><a href="delete.php">Delete Student</a></li>
                            <li><a href="grade.php">Edit Grade</a></li>
                            <li  class ="active"><a href="graduate.php">Graduate<span class="sr-only">(current)</span></a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h3><strong>Results</strong></h3>

            <?php
            print "<p> Information processed at " . date('H:i, jS F Y') . "</p>";

            $name_in = trim($_POST['name_in']);
            $id_in = trim($_POST['id_in']);
            /*
            if (!$id_in) {
                echo '<div class="col-lg-3">';
                echo '<div class = "alert alert-dismissible alert-danger">';
                echo '<button type="button" class="close" data-dismiss="alert"></button>';
                echo '<a href = "/project/view.html" class = "alert-link">No, click here to go back</a> and enter a student ID #.';
                echo '</div>';
                echo '</div>';
                
                exit;
            }
            */
            ?>

            <span class="col-lg-2">
                <div class="panel panel-primary ">

                    <div class="panel-heading" style="font-size: 1.3em">     
                        Status
                    </div>

                    <div class="panel-body ">
                        <div class="panel-content">
                            <?php

                            function db_connect() {

                                // Define connection as a static variable, to avoid connecting more than once 
                                static $myconnection;

                                // Try and connect to the database, if a connection has not been established yet
                                if (!isset($myconnection)) {
                                    
                                    $myconnection = mysqli_connect('localhost', 'root', '', 'info');
                                }

                                // If connection was not successful, handle the error
                                if ($myconnection === false) {
                                    // Handle error - notify administrator, log to a file, show an error screen, etc.
                                    echo " ". mysqli_connect_error() ." ";
                                }
                                return $myconnection;
                            }

                            print "<h5 class=\"text-primary\">Values Entered</h5>";
                            print " &nbsp; <div class=\"label label-primary\">Name: $name_in</div><br>";
                            print " &nbsp; <div class=\"label label-primary\">ID#: $id_in </div><br>";

                            $myconnection = db_connect();
                            
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

                                $queryCheck = "SELECT name
                                      FROM students
                                      WHERE students.SID = $id_in";

                                $resultCheck = mysqli_query($myconnection, $queryCheck)or die('Query failed: ' . mysql_error());
                                $rowCheck = mysqli_fetch_assoc($resultCheck);
                                $nameCheck = $rowCheck['name'];

                                if (strtolower($nameCheck) == strtolower($name_in)) {
                                    echo "<div class=\" label label-success\"> $name_in was found</div><br>";
                                } else {
                                    echo '<div class=" label label-danger">Name and ID do not match</div><br>';
                                    mysqli_close($myconnection);
                                    exit;
                                }

                                mysqli_free_result($resultCheck);

                            }
                            ?>

                        </div>

                    </div>
                </div>
            </span>

            <span class = "col-lg-7">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="font-size: 1.3em">     
                        Graduation Status
                    </div>
                    <div class="panel-body">

                        <?php
                        //Global constants for grade -> grade points conversion
                        define('A+', 4.0, true);
                        define('A', 4.0, true);
                        define('A-', 3.7, true);
                        define('B+', 3.3, true);
                        define('B', 3.0, true);
                        define('B-', 2.7, true);
                        define('C+', 2.3, true);
                        define('C', 2.0, true);
                        define('C-', 1.7, true);
                        define('D+', 1.3, true);
                        define('D', 1.0, true);
                        define('D-', 0.7, true);
                        define('F', 0.0, true);

                        //Global flag variable--switched to 0 if any requirement isn't satisfied
                        $canGraduate = 1;
                        
                        //Global string--if any requirement isn't satisfied, the reason is appended to this string.
                        $reason = "";
                        
                        function conditions(){
                            global $id_in;
                            global $myconnection;
                            global $canGraduate;
                            global $reason;
                            
                            $queryCond = "SELECT * FROM conditions WHERE $id_in = SID";
                            $resultCond= mysqli_query($myconnection, $queryCond)or die('Query failed: ' . mysql_error());
                            echo "<br>Conditions<br>";
                            if (mysqli_num_rows($resultCond) < 1){
                                echo "<p class = text-info> &nbsp; &nbsp; &nbsp; none </p>";
                            }
                            
                            //Go through every class/row in the conditions table
                            while ($rowCond = mysqli_fetch_assoc($resultCond)){
                                $condClass = $rowCond['CID'];
                                
                                //for every class in conditions table, make sure it exists in the enrollment table
                                $query = "SELECT *
                                          FROM enrollment
                                          WHERE SID = $id_in AND
                                          CID = $condClass ";
                                $result= mysqli_query($myconnection, $query)or die('Query failed: ' . mysql_error());
                                
                                if (!$result || (mysqli_num_rows($result) < 1)){
                                    echo "<br><p class='text-danger'> &nbsp; &nbsp; &nbsp; Condition class $condClass was not taken</p>";
                                    $reason = $reason . "<p class='text-danger'>Condition class $condClass was not taken.</p>";
                                    $canGraduate = 0;
                                }else{
                                    echo "<br><p class='text-success'> &nbsp; &nbsp; &nbsp;  Condition class $condClass was taken</p>";
                                }
                                //mysqli_free_result($result);
                            }
                            //TODO?
                        }
                        
                        function numCreditsEarned() {
                            global $id_in;
                            global $myconnection;

                            $queryCreditsEarned = "SELECT SUM(credits)
                                                   FROM courses,
                                                        students,
                                                        enrollment
                                                   WHERE students.SID = $id_in AND
                                                         enrollment.SID = students.SID AND
                                                         enrollment.CID = courses.CID AND
                                                         courses.groupID > 0 AND
                                                         enrollment.grade <> 'F'
                                                   ";

                            $resultCreditsEarned = mysqli_query($myconnection, $queryCreditsEarned)or die('Query failed: ' . mysql_error());
                            $rowCreditsEarned = mysqli_fetch_assoc($resultCreditsEarned);
                            $creds = $rowCreditsEarned['SUM(credits)'];
                            mysqli_free_result($resultCreditsEarned);
                            return $creds ;
                        }
                        
                        function gpa(){
                            $counter = 0;
                            global $id_in;
                            global $myconnection;
                            $cumulativeGP = 0;

                            $queryGpa = "SELECT grade
                                                FROM students,enrollment,courses
                                                WHERE students.SID = $id_in AND
                                                    enrollment.SID = students.SID AND
                                                    courses.CID = enrollment.CID AND
                                                    courses.groupID > 0 AND
                                                    enrollment.grade <> 'F'";
                            
                            $resultGpa = mysqli_query($myconnection, $queryGpa)or die('Query failed: ' . mysql_error());
                            
                            //Calculate GPA
                            while ($rowGPA = mysqli_fetch_assoc($resultGpa)){
                                $counter += 1;
                                $grade = $rowGPA['grade'];
                                @$gradePoint = constant($grade);
                                $cumulativeGP += $gradePoint;                              
                            }
                            if ($counter == 0){
                                mysqli_free_result($resultGpa);
                                return 0;
                            }
                            $cumGPA = $cumulativeGP / $counter;
                            mysqli_free_result($resultGpa);
                            return $cumGPA;
                        }
                        
                        function numBelowB(){
                            global $id_in;
                            global $myconnection;
                            $queryNumBelowB = "SELECT COUNT(*)
                                               FROM enrollment
                                               WHERE enrollment.SID = $id_in AND
                                                     (enrollment.grade LIKE 'C%' OR
                                                      enrollment.grade LIKE 'D%' OR
                                                      enrollment.grade LIKE 'F%' OR                                                     
                                                      enrollment.grade LIKE 'B-')";
                            
                            $resultNumBelowB = mysqli_query($myconnection, $queryNumBelowB)or die('Query failed: ' . mysql_error());
                            $rowNumBelowB = mysqli_fetch_assoc($resultNumBelowB);
                            $num = $rowNumBelowB['COUNT(*)'];
                            mysqli_free_result($resultNumBelowB);
                            return $num;
                        }
                        
                        function CoreAndElectives(){
                            global $id_in;
                            global $myconnection;
                            global $canGraduate;
                            global $reason;
                                                        
                            echo "<br>Core Classes: <br>";
                            //find algorithms
                            $queryCoreAndElective = "SELECT courses.CID as cidAlg
                                      FROM courses, students, enrollment
                                      WHERE students.SID = $id_in AND
                                            students.SID = enrollment.SID AND
                                            enrollment.CID = courses.CID AND
                                            courses.CID = 915030 AND
                                            courses.groupID = 1 ";
                            
                            $resultCoreAndElective = mysqli_query($myconnection, $queryCoreAndElective)or die('Query failed: ' . mysql_error());
                            $rowCoreAndElective = mysqli_fetch_assoc($resultCoreAndElective);
                            
                            if (915030 != $rowCoreAndElective['cidAlg']){
                                $canGraduate = 0;
                                echo "<br><p class='text-danger'> &nbsp; &nbsp; &nbsp; Algorithms was not taken</p>";
                                $reason = $reason . "<p class='text-danger'>You cannot graduate because algorithms was not taken.</p>";
                                mysqli_free_result($resultCoreAndElective);
                            }else{
                                echo "<p class = 'text-info'> &nbsp; &nbsp; &nbsp; Algorithms (" . $rowCoreAndElective['cidAlg']. ") Taken</p>";
                                mysqli_free_result($resultCoreAndElective);
                            }
                           
                            $queryGroup2 = "SELECT courses.CID as cid2
                                            FROM courses, students, enrollment
                                            WHERE courses.groupID = 2 AND
                                            students.SID = $id_in AND
                                            students.SID = enrollment.SID AND
                                            enrollment.CID = courses.CID";
                            
                            $queryGroup3 = "SELECT courses.CID as cid3
                                            FROM courses, students, enrollment
                                            WHERE courses.groupID = 3 AND
                                            students.SID = $id_in AND
                                            students.SID = enrollment.SID AND
                                            enrollment.CID = courses.CID";
                            
                            $queryGroup4 = "SELECT courses.CID as cid4
                                            FROM courses, students, enrollment
                                            WHERE courses.groupID = 4 AND
                                            students.SID = $id_in AND
                                            students.SID = enrollment.SID AND
                                            enrollment.CID = courses.CID";
                            $resultGroup2 = mysqli_query($myconnection, $queryGroup2)or die('Query failed: ' . mysql_error());
                            $resultGroup3 = mysqli_query($myconnection, $queryGroup3)or die('Query failed: ' . mysql_error());
                            $resultGroup4 = mysqli_query($myconnection, $queryGroup4)or die('Query failed: ' . mysql_error());
                            
                            $rowGroup2 = mysqli_fetch_assoc($resultGroup2);
                            $rowGroup3 = mysqli_fetch_assoc($resultGroup3);
                            $rowGroup4 = mysqli_fetch_assoc($resultGroup4);
                            
                            $group2Class = $rowGroup2['cid2'];
                            $group3Class = $rowGroup3['cid3'];
                            $group4Class = $rowGroup4['cid4'];

                            mysqli_free_result($resultGroup2);
                            mysqli_free_result($resultGroup3);
                            mysqli_free_result($resultGroup4);

                            echo "<p class = 'text-info'> &nbsp; &nbsp; &nbsp; Group 2: ".$group2Class."</p>";
                            echo "<p class = 'text-info'> &nbsp; &nbsp; &nbsp; Group 3: ".$group3Class."</p>";
                            echo "<p class = 'text-info'> &nbsp; &nbsp; &nbsp; Group 4: ".$group4Class."</p>";
                            
                            if (empty($group2Class)){
                                $canGraduate = 0;
                                $reason= $reason . '<p class="text-danger">You cannot graduate because you have not taken a class from group 2.</p>';
                                $group2Class = -1;
                            }
                            if (empty($group3Class)){
                                $canGraduate = 0;
                                $reason= $reason . '<p class="text-danger">You cannot graduate because you have not taken a class from group 3.</p>';
                                $group3Class = -1;
                            }
                            if (empty($group4Class)){
                                $canGraduate = 0;
                                $reason= $reason . '<p class="text-danger">You cannot graduate because you have not taken a class from group 4.</p>';
                                $group4Class = -1;
                            }
                            if ( ($group2Class != -1) && ($group3Class != -1) && ($group4Class != -1) ){   
                                echo '<br><p class="text-success"> &nbsp; &nbsp; &nbsp; You have taken a class from group 2, 3, and 4.</p>';
                            }
                            
                            $queryElectives = "SELECT SUM(courses.credits)
                                               FROM courses, enrollment, students
                                               WHERE students.SID = $id_in AND
                                                     students.SID = enrollment.SID AND
                                                     enrollment.CID = courses.CID AND
                                                     courses.CID <> $group2Class AND
                                                     courses.CID <> $group3Class AND
                                                     courses.CID <> $group4Class AND 
                                                     courses.CID <> 915030 AND
                                                     courses.groupID > 0";
                            
                            $resultElectives= mysqli_query($myconnection, $queryElectives)or die('Query failed: ' . mysql_error());
                            $rowElectives = mysqli_fetch_assoc($resultElectives);
                            $electiveCredits = $rowElectives['SUM(courses.credits)'];
                            mysqli_free_result($resultElectives);
                            
                            if (empty($electiveCredits)){
                                $electiveCredits = 0;
                            }
                            
                            if ($electiveCredits >= 18){
                                echo "<br>Elective credits earned = $electiveCredits <p class='text-success'> You have 18 or more elective credits</p>";
                            }else{
                                echo "<br>Elective credits earned = $electiveCredits <p class='text-danger'> You do not have 18 or more elective credits</p>";
                                $canGraduate = 0;
                                $reason= $reason . '<p class="text-danger">You cannot graduate because you have taken less than 18 elective credits.</p>';
                            }                        
                        }
                        
                        conditions();
                        
                        $gpa = gpa();
                        if ($gpa >= 3.0){
                            echo "<br>GPA = $gpa <p class='text-success'> Your GPA is above 3.0(B) or better</p>";
                        }else{
                            echo "<br>GPA = $gpa <p class='text-danger'> Your GPA is not above 3.0(B)</p>";
                            $reason= $reason . '<p class="text-danger">You cannot graduate because your GPA is not above 3.0.</p>';
                            $canGraduate = 0;
                        }
                        
                        $credits = numCreditsEarned();
                        if ($credits >= 30){
                            echo "<br>Credits Earned = $credits <p class='text-success'> You have 30 or more credits.</p>";
                        }else{
                            echo "<br>Credits Earned = $credits <p class='text-danger'> You do not have 30 or more credits.</p>";
                            $reason= $reason . '<p class="text-danger">You cannot graduate because you do not have 30 or more credits.</p>';
                            $canGraduate = 0;
                        }
                        
                        $numBelowB = numBelowB();
                        if ($numBelowB > 2){
                            echo "<br>Grades below B = $numBelowB <p class='text-danger'> You have more than 2 grades below B</p>";
                            $reason= $reason . '<p class="text-danger">You cannot graduate because you have more than 2 grades below B.</p>';
                            $canGraduate = 0;
                        }else{
                            echo "<br>Grades below B = $numBelowB <p class='text-success'> You have 2 or less grades below B.</p>";  
                        }
                        
                        CoreAndElectives();
                        
                        //Check glboal to make final decision
                        if ($canGraduate) {
                            echo "<br><strong>Congratulations, you can graduate!</strong><br>";
                        }else{
                            //print out reason global to see unsatisfied requirements.
                            echo "<br><strong>You cannot graduate because: </strong><br><br>".$reason ."<br>";
                        }  
                        
                        mysqli_close($myconnection);
                        
                        ?>

                    </div>
                </div>
            </span>

            <span class="col-lg-3">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="font-size: 1.3em">     
                        Student Information
                    </div>
                    <div class="panel-body">
                        <?php 
                        
                        ?>
                    </div>
                </div>
            </span>

        </div>
        <footer class = "navbar-fixed-bottom" style="text-align: center">Copyright 2016</footer>
    </body>   
</html>
