<?php
Print "<h2>" . $title . "</h2>";
$str = $alpha ='A';
while (strlen($str) <= 1)
{
    Print "<form action=\"";
    Print htmlspecialchars($_SERVER["PHP_SELF"]);
    Print "\" method=\"post\">\n";
    Print "<fieldset class=\"input\">\n";
    Print "<input type=\"hidden\" name=\"" . $start;
    Print "\" value=\"". (strlen($str) > 1 ? $str : $str++) . "\"/>\n";
    Print "<input type=\"hidden\" name=\"" . $middle;
    Print "\" value=\"" . (strlen($str) > 1 ? $str : $str++) . "\"/>\n";
    if (strlen($str) > 1)
        $str = 'Z';
    Print "<input type=\"submit\" class=\"link\" name=\"COMP\" ";
    Print "value=\"" . $alpha . " - " . $str . "\"/>\n";
    Print "<input type=\"hidden\" name=\"" . $end . "\" value=\"";
    Print $str++ . "\"/>\n";
    Print "<input type=\"hidden\" name=\"" .$action ."_action\" ";
    Print "value=\"2\"/>\n";
    Print "</fieldset>\n";
    Print "</form>\n";
    $alpha = $str;
}   
?>
