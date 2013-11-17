<?php
Print "<h2>Departments</h2>\n";
$query = sprintf("SELECT DISTINCT(dept_code) FROM Requires ORDER BY dept_code");
if ($result = mysqli_query($this->con, $query))
{
    while ($row = mysqli_fetch_assoc($result))
    {
        Print "<form method=\"post\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\">\n";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"dept\" value=\"" . $row['dept_code'] ."\"/>\n";
        Print "<input type=\"hidden\" name=\"course\" value=\"" . $row['course_number'] . "\"/>\n";
        Print "<input type=\"submit\" class=\"link\" name=\"COMP\" value=\"" . $row['dept_code'] . "\"/>\n";
        Print "<input type=\"hidden\" name=\"filter_action\" value=\"2\"/>\n";
        Print "</fieldset>";
        Print "</form>\n";
    }
}
?>
