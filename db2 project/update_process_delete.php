<!--
Glen Anderson
UMass Lowell Computer Science
DB II Website
update_process_delete.php
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

            $id_in = trim($_POST['id_in_remove']);
            $name_in = trim($_POST['name_in_remove']);
            $course_in = trim($_POST['course_in_remove']);
            $term_in = trim($_POST['term_in_remove']);
            $year_in = trim($_POST['year_in_remove']);

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
                        //get course id
                        $query_classID = "SELECT * FROM courses WHERE CID = $course_in ";
                        $resID = mysqli_query($myconnection, $query_classID) or die('Query failed: ' . mysql_error());

                        $row2 = mysqli_fetch_assoc($resID);
                        $courseID = $row2['CID'];

                        //Delete the class
                        mysqli_free_result($resID);
                         echo "<br>Course: $course_in<br>";
                        $query_delClass = " DELETE 
                                            FROM enrollment
                                            WHERE EXISTS (SELECT *
                                                            FROM courses
                                                            WHERE courses.CID = $courseID AND
                                                                courses.CID = enrollment.CID AND
                                                                enrollment.SID = $id_in AND
                                                                enrollment.yearID = $year_in AND
                                                                enrollment.semesterID = '$term_in')";

                        $res = mysqli_query($myconnection, $query_delClass);

                        //Report to user whether or not class was successfully deleted
                        if (mysqli_affected_rows($myconnection) > 0) {
                            //if ($res) {
                            echo '<p class="text-success">Class dropped</p>';
                        } else {
                            // echo "<br>no Sucess! ". mysql_error()."<br>";
                            echo '<p class="text-danger">Class could not be dropped</p>';
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
