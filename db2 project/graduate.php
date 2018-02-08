<!--
Glen Anderson
UMass Lowell Computer Science
DB II Website
graduate.php
-->
<!DOCTYPE html>
<html>
    <head>

        <!--cdn Sandstone bootstrap theme -->
        <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/sandstone/bootstrap.min.css" rel="stylesheet" integrity="sha256-oqtj+Pkh1c3dgdH6V9qoS7qwhOy2UZfyVK0qGLa9dCc= sha512-izanB/WZ07hzSPmLkdq82m5xS7EH/qDMgl5aWR37EII+rJOi5c6ouJ3PYnrw6K+DWQcnMZ+nO1NqDr6SBKLBDg==" crossorigin="anonymous">

        <!-- my css file and a cdn google font -->
        <style>@import url(./styles/my_css.css) </style> 
        <link rel="stylesheet" type="text/css" href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,200italic,300,300italic,600,400italic,600italic,700,700italic,900italic,900'>
            
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
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="./index.html">  &nbsp; SIS &nbsp; &nbsp; </a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="view.html">View Student Info</a></li>
                            <li><a href="new.html">New Student</a></li>
                            <li><a href="update.php">Update Schedule</a></li>
                            <li><a href="delete.php">Delete Student</a></li>
                            <li><a href="grade.php">Edit Grade</a></li>
                            <li  class ="active"><a href="graduate.php">Graduate <span class="sr-only">(current)</span></a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h3><strong>Graduation Status</strong></h3>
            <!--<div class ="row">
                 <hr class = "col-lg-5">
            </div>-->

            <!-- ID# dropdown -->
            <div class="col-lg-8">
                <h3>Form</h3>
                <form action ="graduate_process.php" role = "form" method = "post">
                    <br>
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
                    
                    <!-- Name dropdown -->
                    <div class ="row">
                        <div class="form-group col-lg-4 ">
                            <label>Name</label>
                            <select name="name_in">
                                <?php
                                $query2 = "Select name FROM students";
                                $result2 = mysqli_query($myconnection, $query2);

                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                    $name = $row2["name"];
                                    echo "<option>  $name  </option>";
                                }
                                mysqli_free_result($result2);
                                
                                mysqli_close($myconnection);
                                ?>  
                            </select>
                        </div>
                    </div>

                   <div class="row"></div>
                   <br>
                   
                   <div class="col-lg-5">
                        <input type="submit" name = "Submit" class="btn-primary " value = " Submit Info ">
                       <!-- <button type="button" class="btn btn-primary">Primary</button>-->
                   </div>
                
                </form> 
            </div>
            <div class=" col-lg-2">
                <!-- Placeholder -->
            </div>

        </div>

        <footer class = "navbar-fixed-bottom" style="text-align: center">Copyright 2016</footer>
    </body>    
</html>