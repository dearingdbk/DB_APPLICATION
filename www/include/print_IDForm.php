<?php        

if (isset($_SESSION['id_number']))
{
    Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
    Print "<fieldset class=\"input\">";
    Print "<input type=\"hidden\" name=\"drop_id\" value=\"3\"/>";
    Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $_SESSION['id_number'] . " [x]\"/>";
    Print "</fieldset>";
    Print "</form>";
}
else
{
    Print "<h2>Student ID</h2>\n";
    Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
    Print "<fieldset class=\"input\">";
    Print "<input type=\"text\" name=\"id_number\" />";
    Print "<input type=\"submit\"  name=\"submit\" value=\"submit\"/>";
    Print "</fieldset>";
    Print "</form>";
}
?>
