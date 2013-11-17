<?php

Print "<h2>Authors</h2>";
switch($_SESSION['filter_action'])
{
case 1:
    $query =  "SELECT DISTINCT(SUBSTRING(family_name FROM 1 FOR 1)) ";
    $query .= "FROM Author WHERE family_name ";
    $query .= sprintf("REGEXP \"^[%s-%s]\" ", $_SESSION['start'], $_SESSION['end']);
    break;
case 2:
    $query =  "SELECT DISTINCT(SUBSTRING(a.family_name FROM 1 FOR 1)) ";
    $query .= "FROM Written a INNER JOIN Requires b ON a.isbn = b.isbn ";
    $query .= sprintf("WHERE b.dept_code = \"%s\" ", $_SESSION['dept']);
    $query .= sprintf("AND family_name REGEXP \"^[%s-%s]\" ", $_SESSION['start'], $_SESSION['end']);
    break; 
case 3:
    $query = "SELECT DISTINCT(SUBSTRING(a.family_name FROM 1 FOR 1)) ";
    $query .= "FROM Written a INNER JOIN Requires b ON a.isbn = b.isbn ";
    $query .= sprintf("WHERE b.dept_code = \"%s\" ", $_SESSION['dept']);
    $query .= sprintf("AND b.course_number = \"%s\" ", $_SESSION['course']);
    $query .= sprintf("AND family_name REGEXP \"^[%s-%s]\" ", $_SESSION['start'], $_SESSION['end']);
    break; 
default:
    $query =  "SELECT DISTINCT(SUBSTRING(family_name FROM 1 FOR 1)) ";
    $query .= "FROM Author WHERE family_name ";
    $query .= sprintf("REGEXP \"^[%s-%s]\" ", $_SESSION['start'], $_SESSION['end']);
    break; 

}   
if ($result = mysqli_query($this->con, $query))
{   
    Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
    Print "<fieldset class=\"input\">";
    Print "<input type=\"hidden\" name=\"author_action\" value=\"1\"/>";
    Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $_SESSION['start'] ." - " .      $_SESSION['end'] . " [x]\"/>";
    Print "</fieldset>";
    Print "</form>";
    while ($row = mysqli_fetch_array($result))
    {
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"alphachar\" value=\"" . $row[0] ."\">";
        Print "<input type=\"submit\" class=\"link\" name=\"submit\" value=\"" . $row[0] . "\">";
        Print "<input type=\"hidden\" name=\"author_action\" value=\"3\">\n";
        Print "</fieldset>";
        Print "</form>";
    }   
}       
else    
{       
    echo "The selected query failed on execution.\n";
    printf("<br/>Errormessage: %s\n", $this->con->error);
} 

?>
