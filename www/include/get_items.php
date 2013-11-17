<?php        
$check = 0;
if(empty($this->items) && !isset($_SESSION['confirmation']))
{   
    echo "<h2> No items in your cart </h2>";
}
else if (isset($_SESSION['confirmation']))
{
    Print "<h2>Order placed <br/> Your order confirmation number is:";
    printf("<span id=\"order_id\"> %s </span></h2>", $_SESSION['confirmation']);
    unset($_SESSION['confirmation']);
}
else
{   
    Print "<table id=\"cart_order\">\n";
    Print "<tr>\n";
    Print "<th>ISBN</th>\n";
    Print "<th>Title</th>\n";
    Print "<th>Price</th>\n";
    Print "<th>Qty</th>\n";
    Print "<th> </th>\n";
    Print "</tr>\n"; 

    foreach($this->items as $isbn => $dingo)
    {   
        $alt = $check == 1 ? " class=\"alt\" " : "";
        Print "<tr" . $alt . ">\n";
        Print "<td>" . $isbn . "</td>\n";
        Print "<td>" . $dingo['title'] . "</td>\n";
        Print "<td>$" . $dingo['price'] / 100 . "</td>\n";
        Print "<td>" . $dingo['qty'] . "</td>\n";
        Print "<td>";
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\" >";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"cart_action\" value=\"2\" />";
        Print "<input type=\"hidden\" name=\"isbn\" value=\"". $isbn  ."\" />";
        Print "<input type=\"image\" src=\"images/close.png\" alt=\"remove\">";
        Print "</fieldset>";
        Print "</form>";
        Print "</tr>\n";
        $check = 1 - $check;
    } 
    Print "</table>\n";
}




Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\" >";
Print "<fieldset class=\"input\">";
Print "<input type=\"hidden\" name=\"cart_view\" value=\"3\" />";
Print "<input type=\"image\" src=\"images/goback.png\" value=\"continue shopping\">";
Print "</fieldset>";
Print "</form>";
if (!empty($this->items))
{
    Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
    Print "<fieldset class=\"input\">";
    Print "<input type=\"hidden\" name=\"cart_view\" value=\"2\" />";
    Print "<input type=\"image\" src=\"images/trash.png\" alt=\"empty cart\">";
    Print "</fieldset>";
    Print "</form>";     
    if (isset($_SESSION['login_id']))
    {
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"cart_view\" value=\"6\" />";
        Print "<input type=\"image\" src=\"images/logout.png\" alt=\"logout\">";
        Print "</fieldset>";
        Print "</form>";

        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\" id=\"entry_box\">";
        Print "<fieldset class=\"input\">";
        Print "<h2>" . $_SESSION['login_id'] . "</h2>";
        Print "<input type=\"hidden\" name=\"cart_view\" value=\"7\" />";
        Print "<input type=\"submit\" name=\"submit\" value=\"confirm order\">";
        Print "</fieldset>";            
        Print "</form>"; 
    }
    else
    {
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\" id=\"entry_box\">";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"text\" name=\"login_id\" required placeholder=\"Enter ID Number\" /><br/>";
        Print "<input type=\"password\" name=\"login_pwd\" required placeholder=\"Enter Password\" /><br/>";
        Print "<input type=\"hidden\" name=\"cart_view\" value=\"5\" />";
        Print "<input type=\"submit\" name=\"submit\" value=\"checkout\">";
        Print "</fieldset>";
        Print "</form>";
    }
}
?>
