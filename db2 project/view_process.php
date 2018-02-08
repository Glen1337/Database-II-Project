<!--
Glen Anderson
UMass Lowell Computer Science
DB II Website
view_process.php
-->
<!DOCTYPE html>
<html>
    <head>

        <!-- jQuery for bootstrap -->
        <script   src="https://code.jquery.com/jquery-2.2.2.min.js"   integrity="sha256-36cp2Co+/62rEAAYHLmRCPIych47CvdM+uTBJwSzWjI="   crossorigin="anonymous"></script>
        
        <!--cdn Sandstone bootstrap theme -->
        <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/sandstone/bootstrap.min.css" rel="stylesheet" integrity="sha256-oqtj+Pkh1c3dgdH6V9qoS7qwhOy2UZfyVK0qGLa9dCc= sha512-izanB/WZ07hzSPmLkdq82m5xS7EH/qDMgl5aWR37EII+rJOi5c6ouJ3PYnrw6K+DWQcnMZ+nO1NqDr6SBKLBDg==" crossorigin="anonymous">

        <!-- my css file and a cdn google font -->
        <style>@import url(./styles/my_css.css) </style>
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,200italic,300,300italic,600,400italic,600italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>

         <!-- bootstrap -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        
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
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="./index.html"> &nbsp; SIS &nbsp; &nbsp;  </a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="view.html">View Student Info<span class="sr-only">(current)</span></a></li>
                            <li><a href="new.html">New Student</a></li>
                            <li><a href="update.php">Update Schedule</a></li>
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

            //Get name and ID # from form via HTTP POST
            $name_in = trim($_POST['name_in']);
            $id_in = trim($_POST['id_in']);

            //Make sure an ID # was entered
            if (!$id_in) {
                echo '<div class="col-lg-3">';
                echo '<div class = "alert alert-dismissible alert-danger">';
                echo '<button type="button" class="close" data-dismiss="alert"></button>';
                echo '<a href = "/project/view.html" class = "alert-link">No ID entered, click here to go back</a> and enter a student ID #.';
                echo '</div>';
                echo '</div>';;
                exit;
            }  
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

                            //Conenct to MySQL database
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

                                //Make sure name is not numeric and ID # is numeric
                                if ((is_numeric($name_in)) || !is_numeric($id_in) ){
                                    echo '<div class=" label label-danger">Invalid ID or name</div>';
                                    mysqli_close($myconnection);
                                    exit;
                                }
                            //Query to make sure the entered ID # and name match a student    
                            $queryCheck = "SELECT name
                                      FROM students
                                      WHERE students.SID = $id_in";

                            $resultCheck = mysqli_query($myconnection, $queryCheck)or die('Query failed: ' . mysql_error()) ;

                            $rowCheck = mysqli_fetch_assoc($resultCheck);
                            $nameCheck = $rowCheck['name'];

                            //If the entered name and name of the student with the entered ID # match, student is valid
                            if(strtolower($nameCheck) == strtolower($name_in)){
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
                        Class History
                    </div>
                    <div class="panel-body">

                        <!-- Class History table -->
                        <table class='table table-striped table-hover '>
                            <thead> 
                                <tr>
                                    <th>Course</th>
                                    <th>Section</th>
                                    <th>Teacher</th>
                                    <th>Year</th>
                                    <th>Semester</th>
                                    <th>Grade</th>
                                    <th>Credits</th>
                                    <th>Group</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php
                                //Query that gets informaton about a students enrollment history, and
                                //information about the classes taken
                                $query = "SELECT enrollment.CID, 
                                            courses.name, 
                                            courses.credits, 
                                            enrollment.yearID, 
                                            enrollment.semesterID, 
                                            enrollment.grade, 
                                            enrollment.secID, 
                                            section.IID, 
                                            courses.groupID,
                                            instructors.name as in_name   
                                        FROM students, enrollment, courses, section, instructors
                                        WHERE students.SID = $id_in AND "
                                        . "students.SID = enrollment.SID AND "
                                        . "courses.CID = enrollment.CID AND "
                                        
                                        . "section.CID = enrollment.CID AND "
                                        . "section.SecID = enrollment.SecID AND "
                                        . "section.yearID = enrollment.yearID AND "
                                        . "section.semesterID = enrollment.semesterID AND "
                                        . "section.IID = instructors.IID";

                                $result = mysqli_query($myconnection, $query) or die('Query failed: ' . mysql_error());
                                
                                //Class history row 
                                while ($row = mysqli_fetch_assoc($result)) {
                                    //echo "course: " . $row['name'] . Grade: " . $row['grade'] . "<br>";
                                    echo "<tr>";
                                    echo "<td>".$row['name']."</td>";
                                    echo "<td>&nbsp; &nbsp; &nbsp; ".$row['secID']."</td>";
                                    echo "<td>".$row['in_name']."</td>";
                                    echo "<td>".$row['yearID']."</td>";
                                    echo "<td>&nbsp; &nbsp; &nbsp; &nbsp;".$row['semesterID']."</td>";
                                    echo "<td>&nbsp; &nbsp; &nbsp; ".$row['grade']."</td>";
                                    echo "<td>&nbsp; &nbsp; &nbsp; &nbsp;".$row['credits']."</td>";
                                    echo "<td>&nbsp; &nbsp; &nbsp; &nbsp;".$row['groupID']."</td>";
                                    echo "</tr>";   
                                }
                                
                                //Get # of credits -- classes with grades of 'F' do not count
                                $credit_query = "SELECT SUM(credits)
                                                 FROM students, enrollment, courses
                                                 WHERE students.SID = $id_in AND
                                                    students.SID = enrollment.SID AND
                                                    enrollment.CID = courses.CID AND
                                                    (enrollment.grade LIKE 'A%' OR
                                                     enrollment.grade LIKE 'B%' OR
                                                     enrollment.grade LIKE 'C%' OR
                                                     enrollment.grade LIKE 'D%')";
                                
                                $result2 = mysqli_query($myconnection, $credit_query) or die('Query failed: ' . mysql_error());
                                while ($row2 = mysqli_fetch_assoc($result2)){
                                    $totalCredits =  $row2['SUM(credits)'];        
                                }
                                mysqli_free_result($result2);
                                //mysqli_close($myconnection);
                                 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </span>
     
            <span class="col-lg-3">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="font-size: 1.3em">     
                        Student Information
                    </div>
                    
                    <!-- Student Information panel-->
                    <div class="panel-body">
                        <?php
                        
                        $adviser_query = "SELECT instructors.name AS n2 FROM instructors, students WHERE students.SID = $id_in AND students.IID = instructors.IID";
                        $adviser_result = mysqli_query($myconnection, $adviser_query) or die('Query failed: ' . mysql_error());
                        $row = mysqli_fetch_assoc($adviser_result);
                        $adviser = $row["n2"] . "<br>";
                        mysqli_free_result($adviser_result);

                        $query = "SELECT * FROM students WHERE SID = $id_in AND name = '$name_in'";
                        $student_result = mysqli_query($myconnection, $query) or die('Query failed: ' . mysql_error());

                        $num_results = mysqli_num_rows($student_result);
                        if ($num_results < 1) {
                            echo "No results found";
                        }

                        for ($i = 0; $i < $num_results; $i++) {

                            $row = mysqli_fetch_assoc($student_result);

                            echo "<span class='text-info'>Name: </span>" . $row["name"] . "<br>";
                            echo "<span class='text-info'>ID #: </span>" . $row["SID"] . "<br>";
                            echo "<span class='text-info'>Career: </span>" . $row["career"] . "<br>";
                            echo "<span class='text-info'>Degree: </span>" . $row["degreeHeld"] . "<br>";
                            echo "<span class='text-info'>Major: </span>" . $row["major"] . "<br>";
                            echo "<span class='text-info'>Advisor : </span>" . $adviser ;
                            echo "<span class='text-info'>Credits : </span>" . $totalCredits . "<br>";
                        }
                        
                        mysqli_free_result($student_result);
                        mysqli_close($myconnection);
                        ?>
                    </div>
                </div>
            </span>
        </div>
        <footer class = "navbar-fixed-bottom" style="text-align: center">Copyright 2016</footer>
    </body>   
</html>
