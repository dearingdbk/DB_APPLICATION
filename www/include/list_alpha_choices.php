<?php
Print "<h2>" . $title . "</h2>";
$str = $alpha ='A';
while (strlen($str) <= 1)
{
    Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">\n";
    Print "<fieldset class=\"input\">\n";
    Print "<input type=\"hidden\" name=\"" . $start;
    Print "\" value=\"". (strlen($str) > 1 ? $str : $str++) . "\"/>\n";
    Print "<input type=\"hidden\" name=\"" . $middle;
    Print "\" value=\"" . (strlen($str) > 1 ? $str : $str++) . "\"/>\n";
    if (strlen($str) > 1)
        $str = 'Z';
    Print "<input type=\"submit\" class=\"link\" name=\"COMP\" value=\"" . $alpha . " - " . $str . "\"/>\n";
    Print "<input type=\"hidden\" name=\"" . $end . "\" value=\"" . $str++ . "\"/>\n";
    Print "<input type=\"hidden\" name=\"" .$action ."_action\" value=\"2\"/>\n";
    Print "</fieldset>\n";
    Print "</form>\n";
    $alpha = $str;
}   
?>
