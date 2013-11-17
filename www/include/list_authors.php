<?php
Print "<h2>Authors</h2>";

$query = "SELECT DISTINCT(family_name), given_name  FROM all_book_data ";
$title_act = ""; 
switch ($_SESSION['title_action'])
{
case 2:
    $title_act = sprintf(" AND title REGEXP \"^[%s-%s]\" ",$_SESSION['tstart'], $_SESSION['tend']);
    break;
case 3:
    $title_act = sprintf(" AND title LIKE \"%s%%\" ",$_SESSION['talphachar']);
    break;
default:
    break; 

}   
switch($_SESSION['filter_action'])
{   
case 2:
    $query .= " WHERE ";
    $query .= sprintf("dept_code = \"%s\" ", $_SESSION['dept']);
    $query .= sprintf("AND family_name LIKE \"%s%%\" ", $_SESSION['alphachar']);
    break; 
case 3:
    $query .= sprintf(" WHERE dept_code = \"%s\" ", $_SESSION['dept']);
    $query .= sprintf(" AND course_number = \"%s\" ", $_SESSION['course']);
    $query .= sprintf(" AND family_name LIKE \"%s%%\" ", $_SESSION['alphachar']);
    break;
default:
    $query .= " WHERE family_name ";
    $query .= sprintf("LIKE \"%s%%\"", $_SESSION['alphachar']);
    break;
} 
if ($result = mysqli_query($this->con, $query))
{   
    Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
    Print "<fieldset class=\"input\">";
    Print "<input type=\"hidden\" name=\"author_action\" value=\"2\">";
    Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $alphachar . " [x]\">";
    Print "</fieldset>";
    Print "</form>";
    while ($row = mysqli_fetch_assoc($result))
    {   
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"alphachar\" value=\"" . $alphachar ."\">";
        Print "<input type=\"hidden\" name=\"family_name\" value=\"" . $row['family_name'] ."\">";
        Print "<input type=\"hidden\" name=\"given_name\" value=\"" . $row['given_name'] . "\">";
        Print "<input type=\"submit\" class=\"link\" name=\"submit\" value=\"" . $row['family_name'] . ", " .  $row['given_name'] . "\">";
        Print "<input type=\"hidden\" name=\"author_action\" value=\"4\">\n";
        Print "</fieldset>";
        Print "</form>";
    }
}
else
{
    echo "The selected query failed on execution.";
    printf("<br/>Errormessage: %s\n", $this->con->error);
}
?>
