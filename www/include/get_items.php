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
        Print "<td>" . $dingo['qty'];

        $this->createForm("id=\"increase\"",
            array(array('type' => 'hidden', 'name' => 'isbn', 'value' => $isbn),
            array('type' => 'hidden', 'name' => 'cart_action', 'value' => 3),
            array('type' => 'image', 'src' => 'images/increase.png', 'alt' => 'increase')));
        
        $this->createForm("id=\"decrease\"",
            array(array('type' => 'hidden', 'name' => 'isbn', 'value' => $isbn),
            array('type' => 'hidden', 'name' => 'cart_action', 'value' => 4),
            array('type' => 'image', 'src' => 'images/decrease.png', 'alt' => 'decrease')));

        Print "</td>\n";
        Print "<td>";

        $this->createForm("",
            array(array('type' => 'hidden', 'name' => 'isbn', 'value' => $isbn),
            array('type' => 'hidden', 'name' => 'cart_action', 'value' => 2),
            array('type' => 'image', 'src' => 'images/close.png', 'alt' => 'remove')));

        Print "</tr>\n";
        $check = 1 - $check;
    }
    Print "<tr><th colspan=\"4\">TOTAL</th>";
    Print "<td id=\"order_total\">";
    printf("$%.2f ", $this->print_cart_total() / 100);
    Print "</td></tr>"; 
    Print "</table>\n";
}

$this->createForm("",
    array(array('type' => 'hidden', 'name' => 'cart_view', 'value' => 3),
    array('type' => 'image', 'src' => 'images/goback.png', 'alt' => 'continue shopping')));

if (!empty($this->items))
{
    $this->createForm("",
        array(array('type' => 'hidden', 'name' => 'cart_view', 'value' => 2),
        array('type' => 'image', 'src' => 'images/trash.png', 'alt' => 'empty_cart')));
    if (isset($_SESSION['login_id']))
    {
        $this->createForm("",
            array(array('type' => 'hidden', 'name' => 'cart_view', 'value' => 6),
            array('type' => 'image', 'src' => 'images/logout.png', 'alt' => 'logout')));


        $this->createForm("id=\"entry_box\" > <h2>" . $_SESSION['login_id'] . "</h2" ,
            array(array('type' => 'hidden', 'name' => 'cart_view', 'value' => 7), 
            array('type' => 'submit', 'name' => 'submit', 'value' => 'confirm order')));
    }
    else
    {
        $this->createForm("id=\"entry_box\"",
            array(array('type' => 'hidden', 'name' => 'cart_view', 'value' => 5),
            array('type' => 'text', 'name' => 'login_id', 'place' => 'Enter ID Number'),
            array('type' => 'password', 'name' => 'login_pwd', 'place' => 'Enter Password'),
            array('type' => 'submit', 'name' => 'submit', 'value' => 'confirm order')));
    }
}
?>
