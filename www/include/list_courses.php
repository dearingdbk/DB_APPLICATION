<?php
Print "<h2>Courses</h2>\n"; 
$query = "SELECT * FROM Requires WHERE ";
$query .= sprintf("dept_code = \"%s\" ", $_SESSION['dept']);
$query .= "ORDER BY course_number";
if ($result = mysqli_query($this->con, $query))
{   
    Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]);
    Print "\" method=\"post\">\n";
    Print "<fieldset class=\"input\">";
    Print "<input type=\"hidden\" name=\"filter_action\" value=\"1\"/>\n"; 
    Print "<input type=\"submit\" class=\"backl\" name=\"submit\" ";
    Print "value=\"" . $_SESSION['dept'] . " [x]\"/     >\n";           
    Print "</fieldset>";
    Print "</form>\n";

    while ($row = mysqli_fetch_assoc($result)) 
    {   
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]);
        Print "\" method=\"post\">\n";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"dept\" value=\"";
        Print $row['dept_code'] ."\"/>\n";
        Print "<input type=\"hidden\" name=\"course\" value=\"";
        Print $row['course_number'] . "\"/>\n";
        Print "<input type=\"submit\" class=\"link\" name=\"submit\"";
        Print " value=\"" . $row['dept_code'] ." [";
        Print $row['course_number'] . "] [" . $row['section_code'];
        Print $row['term_number'] . "]\"/>\n";
        Print "<input type=\"hidden\" name=\"filter_action\" value=\"3\"/>\n";
        Print "</fieldset>";
        Print "</form>\n";
    }   
}

?>
