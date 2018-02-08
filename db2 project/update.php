<!--
Glen Anderson
UMass Lowell Computer Science
DB II Website
update.php
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

            <!-- navbar -->
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
                            <li><a href="new.html">New Student<span class="sr-only">(current)</span></a></li>
                            <li class ="active"><a href="#">Update Schedule</a></li>
                            <li><a href="delete.php">Delete Student</a></li>
                            <li><a href="grade.php">Edit Grade</a></li>
                            <li><a href="graduate.php">Graduate</a></li>
                        </ul>
                    </div>

                </div>
            </nav>

            <h3><strong>Update Student Info</strong></h3>

            <!-- ADD A CLASS form -->
            <span class="col-lg-5 well well-sm">
                <legend>Add a class</legend>
                <form action ="update_process_add.php" role = "form" method = "post">

                    <div class= "form-group col-lg-3">
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

                    <div class ="row">
                        <div class="form-group col-lg-4 ">
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

                    <div class="form-group col-lg-6">
                        <label>Course Name</label>
                        <select name="course_in">
                            <?php
                            $query3 = "Select DISTINCT name, CID FROM courses";
                            $result3 = mysqli_query($myconnection, $query3);

                            while ($row = mysqli_fetch_assoc($result3)) {
                                $course = $row["name"];
                                $courseID = $row["CID"];
                                //value is the course ID # of the course
                                echo "<option value='$courseID'>  $course  </option>";
                            }
                            mysqli_free_result($result3)
                            ?>  
                        </select>
                    </div>

                    <div class ="row"></div>

                    <div class="form-group col-lg-3 ">
                        <label>Section</label>
                        <input type="text" name="section_in" class="form-control" placeholder="Section #" maxlength = "30">
                    </div>

                    <div class="form-group col-lg-3 ">
                        <label>Year</label>
                        <input type="text" name="year_in" class="form-control" placeholder="Year" maxlength = "30">
                    </div>

                    <div class="form-group col-lg-3 ">
                        <label>Semester</label>
                        <select name="term_in">
                            <option value='F'>Fall</option>
                            <option value='S'>Spring</option>
                        </select>
                    </div>

                    <div class="row"></div>

                    <div class="col-lg-5 ">
                        <input type="submit" name = "Submit" class="btn btn-primary " value = " Submit Info ">
                    </div>

                </form> 
            </span>

            <span class="col-lg-1"></span>

            <!-- REMOVE A CLASS form -->
            <span class=" well well-sm col-lg-5">
                <legend>Remove a class</legend>
                <form action ="update_process_delete.php" role = "form" method = "post">

                    <div class="row"></div>

                    <div class= "form-group col-lg-3">
                        <label>ID# </label>
                        <select name="id_in_remove">
                            <?php
                            $query4 = "Select SID FROM students";
                            $result4 = mysqli_query($myconnection, $query4);

                            while ($row = mysqli_fetch_assoc($result4)) {
                                $id = $row["SID"];
                                echo "<option>  $id  </option>";
                            }
                            mysqli_free_result($result4);
                            ?>  
                        </select>
                    </div>

                    <div class ="row">
                        <div class="form-group col-lg-4 ">
                            <label>Name</label>
                            <select name="name_in_remove">
                                <?php
                                $query22 = "Select name FROM students";
                                $result22 = mysqli_query($myconnection, $query22);

                                while ($row = mysqli_fetch_assoc($result22)) {
                                    $id = $row["name"];
                                    echo "<option>  $id  </option>";
                                }
                                mysqli_free_result($result22)
                                ?>  
                            </select>
                        </div>
                    </div>
                    
                    <br>
                    
                    <div class="form-group col-lg-6">
                        <label>Course Name</label>
                        <select name="course_in_remove">
                            <?php
                            $query5 = "Select name, CID FROM courses";
                            $result5 = mysqli_query($myconnection, $query5);

                            while ($row = mysqli_fetch_assoc($result5)) {
                                $course = $row["name"];
                                $courseID = $row["CID"];
                                //value is the course ID # of the course
                                echo "<option value='$courseID'>  $course  </option>";
                            }
                            mysqli_free_result($result5)
                            ?>  
                        </select>
                    </div>

                    <div class ='row'></div>


                    <div class="form-group col-lg-3 ">
                        <label>Year</label>
                        <input type="text" name="year_in_remove" class="form-control"  placeholder="Year" maxlength = "30">
                    </div>


                    <div class="form-group col-lg-3 ">
                        <label>Semester</label>
                        <select name="term_in_remove">
                            <option value='F'>Fall</option>
                            <option value='S'>Spring</option>
                        </select>
                    </div>

                    <div class="row"></div>

                    <div class="col-lg-5">
                        <input type="submit" name = "Submit" class="btn btn-primary " value = " Submit Info ">
                    </div>
                
                </form> 
            </span>
        </div>

        <footer class = "navbar-fixed-bottom" style="text-align: center">Copyright 2016</footer>
    </body>    
</html>