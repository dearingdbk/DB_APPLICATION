<?php        

Print "<h2>Titles</h2>";
$query =  "SELECT DISTINCT(SUBSTRING(title FROM 1 FOR 1)) ";
$query .= "FROM all_book_data  WHERE title ";
$query .= sprintf("REGEXP \"^[%s-%s]\" ", $_SESSION['tstart'],
 $_SESSION['tend']);
$query .= " ORDER BY title ";
if ($result = mysqli_query($this->con, $query))
{
    Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]);
    Print "\" method=\"post\">";
    Print "<fieldset class=\"input\">";
    Print "<input type=\"hidden\" name=\"title_action\" value=\"1\"/>";
    Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"";
    Print $_SESSION['tstart'] ." - " . $_SESSION['tend'] . " [x]\"/>";
    Print "</fieldset>";
    Print "</form>";
    while ($row = mysqli_fetch_array($result))
    {
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]);
        Print "\" method=\"post\">";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"talphachar\" value=\"";
        Print $row[0] ."\">";
        Print "<input type=\"submit\" class=\"link\" name=\"submit\" ";
        Print "value=\"" . $row[0] . "\">";
        Print "<input type=\"hidden\" name=\"title_action\" value=\"3\">\n";
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
