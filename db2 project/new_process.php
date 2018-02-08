<!--
Glen Anderson
UMass Lowell Computer Science
DB II Website
new_process.php
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
                            <li><a href="view.html">View Student Info</a></li>
                            <li class ="active"><a href="new.html">New Student<span class="sr-only">(current)</span></a></li>
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

            // take in values from new.html
            $name_in = trim($_POST['name_in']);
            $id_in = trim($_POST['id_in']);
            $career_in = trim($_POST['career_in']);
            $degree_in = trim($_POST['degree_in']);
            $major_in = trim($_POST['major_in']);
            $adviser_in = trim($_POST['adviser_in']);

            //Make sure the student ID # and adviser ID # are numberic
             if ( (!is_numeric($id_in))||
                  (!is_numeric($adviser_in))){
                 
                echo '<div class="col-lg-3">';
                echo '<div class = "alert alert-dismissible alert-danger">';
                echo '<button type="button" class="close" data-dismiss="alert"></button>';
                echo '<strong>Oops!</strong> <a href = "/project/new.html" class = "alert-link">You need to fill out the ID fields with numbers.';
                echo '</div>';
                echo '</div>';
                exit; 
             }
           
             //Check to see that these values are not empty
            if ((empty($name_in)||
                    empty($id_in)||
                    empty($career_in)||
                    empty($degree_in)||
                    empty($major_in)||
                    empty($adviser_in)))
                {                
                echo '<div class="col-lg-3">';
                echo '<div class = "alert alert-dismissible alert-danger">';
                echo '<button type="button" class="close" data-dismiss="alert"></button>';
                echo '<strong>Oops!</strong> <a href = "/project/new.html" class = "alert-link">You need to fill out all of the fields.';
                echo '</div>';
                echo '</div>';
                //??
                //mysqli_close($myconnection);
                exit;
            }
            ?>

            <span class="col-lg-3">
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
                            print " &nbsp; <div class=\"label label-primary\">Career: $career_in </div><br>";
                            print " &nbsp; <div class=\"label label-primary\">Degree: $degree_in </div><br>";
                            print " &nbsp; <div class=\"label label-primary\">Major: $major_in </div><br>";
                            print " &nbsp; <div class=\"label label-primary\">Adviser: $adviser_in </div><br>";
                            
                             $myconnection = mysqli_connect('localhost', 'root', '', 'info'); 

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
                            echo '<div class=" label label-success">Connected to database successfully</div><br>';
                            
                            /* if (get_magic_quotes_gpc()){
                                echo '<div class=" label label-success">GPC Magic is ON</div>';
                            }else{
                                echo '<div class=" label label-success">GPC Magic is OFF</div>';
                            }
                            echo '<br>'; */
                            
                            //Insert student info into students table
                            $query = "INSERT INTO students VALUES (\"$id_in\", \"$name_in\", \"$adviser_in\", \"$major_in\", \"$degree_in\", \"$career_in\")";    
                            $result = mysqli_query($myconnection, $query);

                            if(!$result){
                                echo '<p class="text-danger"> There was an error executing the query: '.mysqli_error($myconnection);
                                echo'</p>';   
                            }
                            
                            $num_results = mysqli_affected_rows($myconnection);

                            echo "<div class=\"label label-success\"> $num_results tuples added successfully </div>";
                            echo '<br><br>';

                            }
                            mysqli_close($myconnection);
                            ?>
                            
                        </div>
                    </div>
                </div>
            </span>
            
           <span class="col-lg-7">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="font-size: 1.3em">     
                        Student Information
                    </div>
                    <div class="panel-body">
                        <div class="panel-content" >
                            <?php
                                echo "<p class='text-info'> Student Information for $name_in added successfully.</p>";
                            ?>
                        </div>
                    </div>
                </div>
            </span>
            
        </div>
        <footer class = "navbar-fixed-bottom" style="text-align: center">Copyright 2016</footer>
    </body>   
</html>
