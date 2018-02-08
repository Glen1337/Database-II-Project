<?php
header('Content-Type: application/json; charset=utf-8');
//header('Content-Type: text/html; charset=utf-8');

//GLOBAL VARS
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

$canGraduate = 1;
$response = array();
$response["reqs"] = array();

//DB CONNECT FUNCTION
function db_connect() {

    // Define connection as a static variable, to avoid connecting more than once 
    static $myconnection;

    // Try and connect to the database, if a connection has not been established yet
    if (!isset($myconnection)) {

        $myconnection = mysqli_connect('localhost', 'root', '', 'info');
    }

    // If connection was not successful, handle the error
    if ($myconnection === false) {
        // Handle error
        echo " " . mysqli_connect_error() . " ";
    }
    return $myconnection;
}

//GET ID
$id_in = trim($_GET['id_in']);

//str_replace('"', "", $id_in);
//str_replace("'", "", $id_in);
//CONNECT TO DB
$myconn = db_connect() or die("error, couldnt connect to database");

//CHECK IF ID IS VALID
if ((empty($id_in)) || (!is_numeric($id_in) )) {
    $response["student"] = "invalid";
    echo json_encode($response);
    mysqli_close($myconn);
    exit;
}

// GET NAME FROM GIVEN ID#
$query = "SELECT name FROM students WHERE SID = $id_in";
$result = mysqli_query($myconn, $query)or die('Query failed: ' . mysql_error());
$row = mysqli_fetch_assoc($result);
$name = $row['name'];

if (is_null($row['name']) || empty($row['name'])){
    $response["student"] = "invalid";
    echo json_encode($response);
    mysqli_close($myconn);
    exit;
}else{
    $response["student"] = "valid";
}

mysqli_free_result($result);

//1ST, CHECK CONDITIONS
// <editor-fold defaultstate="collapsed" desc="Conditions">
$reqConditions = array();
$hasAllCons = 1;
$conFailReason = "";
$conPassReason = "";
$queryCond = "SELECT * FROM conditions WHERE $id_in = SID";
$resultCond = mysqli_query($myconn, $queryCond)or die('Query failed: ' . mysql_error());

if (mysqli_num_rows($resultCond) < 1) {

    $reqConditions["Name"] = "Conditions";
    $reqConditions["Pass"] = "Yes";
    $reqConditions["Reason"] = " No Conditions needed.";
    array_push($response["reqs"], $reqConditions);
} else {

    while ($rowCond = mysqli_fetch_assoc($resultCond)) {
        $condClass = $rowCond['CID'];
        $query = "SELECT * FROM enrollment WHERE SID = $id_in AND CID = $condClass ";
        $result = mysqli_query($myconn, $query)or die('Query failed: ' . mysql_error());
        if (!$result || (mysqli_num_rows($result) < 1)) {
            $conFailReason = $conFailReason . " Has not taken condition class $condClass.";
            $hasAllCons = 0;
            $canGraduate = 0;
        } else {
            $conPassReason = $conPassReason . " Has taken condition class $condClass.";
        }
        //mysqli_free_result($result);
    }
    if ($hasAllCons) {
        $reqConditions["Name"] = "Conditions";
        $reqConditions["Pass"] = "Yes";
        $reqConditions["Reason"] = $conPassReason;
        array_push($response["reqs"], $reqConditions);
    } else {
        $reqConditions["Name"] = "Conditions";
        $reqConditions["Pass"] = "No";
        $reqConditions["Reason"] = $conFailReason;
        array_push($response["reqs"], $reqConditions);
    }
}
// </editor-fold>

//2ND, CHECK NUMBER OF CREDITS EARNED
// <editor-fold defaultstate="collapsed" desc="Credits">
$reqCredits = array();

$queryCreditsEarned = "SELECT SUM(credits)
                       FROM courses,
                            students,
                            enrollment
                       WHERE students.SID = $id_in AND
                             enrollment.SID = students.SID AND
                             enrollment.CID = courses.CID AND
                             courses.groupID > 0 AND
                             enrollment.grade <> 'F' ";

$resultCreditsEarned = mysqli_query($myconn, $queryCreditsEarned)or die('Query failed: ' . mysql_error());
$rowCreditsEarned = mysqli_fetch_assoc($resultCreditsEarned);
$creds = $rowCreditsEarned['SUM(credits)'];

if ($creds >= 30) {
    $reqCredits["Name"] = "Credits";
    $reqCredits["Pass"] = "Yes";
    $reqCredits["Reason"] = " You have 30 or more credits ($creds).";
    array_push($response["reqs"], $reqCredits);
} else {
    $reqCredits["Name"] = "Credits";
    $reqCredits["Pass"] = "No";
    $reqCredits["Reason"] = " You have less than 30 credits ($creds).";
    array_push($response["reqs"], $reqCredits);
    
    $canGraduate = 0;
}
// </editor-fold>

//3RD, CHECK GPA
// <editor-fold defaultstate="collapsed" desc="GPA">
$reqGPA = array();
$counter = 0;
$cumulativeGP = 0;

$queryGpa = "SELECT grade
             FROM students,
                  enrollment,
                  courses
             WHERE students.SID = $id_in AND
                   enrollment.SID = students.SID AND
                   courses.CID = enrollment.CID AND
                   courses.groupID > 0 AND
                   enrollment.grade <> 'F'";

$resultGpa = mysqli_query($myconn, $queryGpa)or die('Query failed: ' . mysql_error());

while ($rowGPA = mysqli_fetch_assoc($resultGpa)) {
    $counter += 1;
    $grade = $rowGPA['grade'];
    $gradePoint = constant($grade);
    $cumulativeGP += $gradePoint;
}
if ($counter == 0) {
    mysqli_free_result($resultGpa);
    $finalGPA = 0;
} else {
    $finalGPA = $cumulativeGP / $counter;
}

if($finalGPA >= 3.0){
    $reqGPA["Name"] = "GPA";
    $reqGPA["Pass"] = "Yes";
    $reqGPA["Reason"] = " GPA greater than or equal to B ($finalGPA).";
}else{
    $reqGPA["Name"] = "GPA";
    $reqGPA["Pass"] = "No";
    $reqGPA["Reason"] =  " GPA less than B ($finalGPA).";
    $canGraduate = 0;
}

array_push($response["reqs"], $reqGPA);
// </editor-fold>

//4TH CHECK NUMBER OF GRADES BELOW B
// <editor-fold defaultstate="collapsed" desc="Num Below B">

$reqNumBelowB = array();

$queryNumBelowB = "SELECT COUNT(*)
                   FROM enrollment
                   WHERE enrollment.SID = $id_in AND
                        (enrollment.grade LIKE 'C%' OR
                         enrollment.grade LIKE 'D%' OR
                         enrollment.grade LIKE 'F%' OR                                                     
                         enrollment.grade LIKE 'B-')";

$resultNumBelowB = mysqli_query($myconn, $queryNumBelowB)or die('Query failed: ' . mysql_error());

$rowNumBelowB = mysqli_fetch_assoc($resultNumBelowB);
$num = $rowNumBelowB['COUNT(*)'];

if ($num > 2){
    
    $reqNumBelowB["Name"] = "# Of Grades Below B";
    $reqNumBelowB["Pass"] = "No";
    $reqNumBelowB["Reason"] = " More than 2 grades below B ($num).";
    
    $canGraduate = 0;
}else{
     
    $reqNumBelowB["Name"] = "# Of Grades Below B";
    $reqNumBelowB["Pass"] = "Yes";
    $reqNumBelowB["Reason"] = " No more than 2 grades below B ($num).";
    
}

array_push($response["reqs"], $reqNumBelowB);

// </editor-fold>

//5TH CHECK CORE AND ELECTIVES
// <editor-fold defaultstate="collapsed" desc="Core and Electives">

$coreElectiveFailReason = "";
$coreElectivePassReason = "";
$hasCoreAndElectives = 1;

$reqCoreAndElectives = array();

$queryAlg = "SELECT courses.CID as cidAlg
                         FROM courses, students, enrollment
                         WHERE students.SID = $id_in AND
                               students.SID = enrollment.SID AND
                               enrollment.CID = courses.CID AND
                               courses.CID = 915030 AND
                               courses.groupID = 1 ";
$resultAlg= mysqli_query($myconn, $queryAlg)or die('Query failed: ' . mysql_error());
$rowAlg = mysqli_fetch_assoc($resultAlg);

if (915030 != $rowAlg['cidAlg']) {
    
    $canGraduate = 0;
    $hasCoreAndElectives = 0;
    $coreElectiveFailReason = $coreElectiveFailReason . " Algorithms 915030 was not taken.";

} else {
    
    $coreElectivePassReason = $coreElectivePassReason . " Algorithms 915030 was taken.";

}

$queryGroup2 = "SELECT courses.CID as cid2
                 FROM courses,
                      students,
                      enrollment
                 WHERE courses.groupID = 2 AND
                       students.SID = $id_in AND
                       students.SID = enrollment.SID AND
                       enrollment.CID = courses.CID";

$queryGroup3 = "SELECT courses.CID as cid3
                 FROM courses,
                      students,
                      enrollment
                 WHERE courses.groupID = 3 AND
                       students.SID = $id_in AND
                       students.SID = enrollment.SID AND
                       enrollment.CID = courses.CID";

$queryGroup4 = "SELECT courses.CID as cid4
                FROM courses,
                     students,
                     enrollment
                WHERE courses.groupID = 4 AND
                      students.SID = $id_in AND
                      students.SID = enrollment.SID AND
                      enrollment.CID = courses.CID";

$resultGroup2 = mysqli_query($myconn, $queryGroup2)or die('Query failed: ' . mysql_error());
$resultGroup3 = mysqli_query($myconn, $queryGroup3)or die('Query failed: ' . mysql_error());
$resultGroup4 = mysqli_query($myconn, $queryGroup4)or die('Query failed: ' . mysql_error());

$rowGroup2 = mysqli_fetch_assoc($resultGroup2);
$rowGroup3 = mysqli_fetch_assoc($resultGroup3);
$rowGroup4 = mysqli_fetch_assoc($resultGroup4);

$group2Class = $rowGroup2['cid2'];
$group3Class = $rowGroup3['cid3'];
$group4Class = $rowGroup4['cid4'];

if (empty($group2Class)) {
    $canGraduate = 0;
    $coreElectiveFailReason = $coreElectiveFailReason . " You haven't taken a course from group 2.";
    $group2Class = -1;
    $hasCoreAndElectives = 0;
}
if (empty($group3Class)) {
    $canGraduate = 0;
    $coreElectiveFailReason = $coreElectiveFailReason . " You haven't taken a course from group 3.";
    $group3Class = -1;
    $hasCoreAndElectives = 0;
}
if (empty($group4Class)) {
    $canGraduate = 0;
    $coreElectiveFailReason = $coreElectiveFailReason . " You haven't taken a course from group 4.";
    $group4Class = -1;
    $hasCoreAndElectives = 0;
}

if (($group2Class != -1) && ($group3Class != -1) && ($group4Class != -1)) {

    $coreElectivePassReason = $coreElectivePassReason . " You've taken a course from group 2, 3, and 4.";
    
}

$queryElectives = "SELECT SUM(courses.credits)
                   FROM courses,
                        enrollment,
                        students
                   WHERE students.SID = $id_in AND
                         students.SID = enrollment.SID AND
                         enrollment.CID = courses.CID AND
                         courses.CID <> $group2Class AND
                         courses.CID <> $group3Class AND
                         courses.CID <> $group4Class AND 
                         courses.CID <> 915030 AND
                         courses.groupID > 0";

$resultElectives = mysqli_query($myconn, $queryElectives)or die('Query failed: ' . mysql_error());
$rowElectives = mysqli_fetch_assoc($resultElectives);
$electiveCredits = $rowElectives['SUM(courses.credits)'];

if ($electiveCredits >= 18) {
    $coreElectivePassReason = $coreElectivePassReason ." 18 or more elective credits taken ($electiveCredits).";
} else {
    $canGraduate = 0;
    $hasCoreAndElectives = 0;
    $coreElectiveFailReason = $coreElectiveFailReason . " Less than 18 elective credits taken ($electiveCredits).";
}

if ($hasCoreAndElectives){
    $reqCoreAndElectives["Name"] = "Core and Electives";
    $reqCoreAndElectives["Pass"] = "Yes";
    $reqCoreAndElectives["Reason"] = $coreElectivePassReason;
    
}else{
    $reqCoreAndElectives["Name"] = "Core and Electives";
    $reqCoreAndElectives["Pass"] = "No";
    $reqCoreAndElectives["Reason"] = $coreElectiveFailReason;
    
}

array_push($response["reqs"], $reqCoreAndElectives);

//</editor-fold>

if ($canGraduate){
    $response["Can Graduate"] = "Yes";
}else{
    $response["Can Graduate"] = "No";
}

//$req = array();
//$req["name"] = $name;
//$req["status"] = "jenny";
//array_push($response["reqs"], $req);

////$req2 = array();
//$req2["message"] = "BAD";
//$req2["id"] = $id_in;
//array_push($response["reqs"], $req2);


echo json_encode($response);

//echo "$name";

mysqli_close($myconn);
?>