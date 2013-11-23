<?php require('inventoryUpdate.php'); ?>
<?php session_start(); 


?>
<!DOCTYPE HTML>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php

if (!$inv && !isset($_SESSION['ainventory']))
{
    $_SESSION['ainventory'] = new InventoryUpdate();
}
$inv = $_SESSION['ainventory'];
$inv->connectToDB("storeadmin", "adminaccount");

?>

</head>
<body>
<?php



if (isset($_POST['username']) && isset($_POST['password']))
{           
    $inv->login_id($_POST['username'], $_POST['password']);
}                           

if (!isset($_SESSION['alogin']))
{   
    echo "<FORM NAME =\"form1\" METHOD =\"POST\" ACTION =\"\">";
    echo "Username: <INPUT TYPE = 'TEXT' Name ='username' maxlength=\"20\">";
    echo "Password: <INPUT TYPE = 'TEXT' Name ='password' maxlength=\"16\">";
    echo "<P align = center>";
    echo "<INPUT TYPE = \"Submit\" Name = \"Submit1\"  VALUE = \"Login\">";
    echo "</P>";
    echo "</FORM>";

}

if (isset($_SESSION['alogin']))
{

    echo "<FORM NAME =\"form2\" METHOD =\"POST\" ACTION =\"\">";
    echo "<input type=\"hidden\" name=\"page_action\" value=\"1\" />";
    echo "<P align = center>";
    echo "<INPUT TYPE = \"Submit\" Name = \"generateReport\" VALUE = \"Generate Report\">";
    echo "</P>";
    echo "</FORM>";

    echo "<FORM NAME =\"form3\" METHOD =\"POST\" ACTION =\"\">";
    echo "<input type=\"hidden\" name=\"page_action\" value=\"2\" />";
    echo "<P align = center>";
    echo "<INPUT TYPE = \"Submit\" Name = \"update\" VALUE = \"Update Inventory\">";
    echo "</P>";
    echo "</FORM>";

    echo "<FORM NAME =\"form4\" METHOD =\"POST\" ACTION =\"\">";
    echo "<P align = center>";
    echo "<input type=\"hidden\" name=\"page_action\" value=\"3\" />";
    echo "<INPUT TYPE = \"Submit\" Name = \"addBook\" VALUE = \"Add a Book\">";
    echo "</P>";
    echo "</FORM>";



    if(isset($_POST['page_action']))
        $_SESSION['page_action'] = $_POST['page_action'];
    else if (!isset($_SESSION['page_action']))
        $_SESSION['page_action'] = 6;

   switch($_SESSION['page_action'])
    {   
    case 1:
        $inv->generateReport();
        break;
    case 2:
        echo "Update Quantity";
        echo "<FORM NAME =\"form5\" METHOD =\"POST\" ACTION =\"\">";
        echo "ISBN: <INPUT TYPE = 'TEXT' Name ='isbn' maxlength=\"17\">";
        echo "Quantity: <INPUT TYPE = 'TEXT' Name ='qty' maxlength=\"16\">";
        echo "<P align = center>";
        echo "<INPUT TYPE = \"Submit\" Name = \"uq\"  VALUE = \"Update\">";
        echo "</P>";
        echo "</FORM>";
        if (isset($_POST['isbn']) && isset($_POST['qty']))
        {
            $inv->update($_POST['qty'], $_POST['isbn']);
            $inv->generateReport();
        }

        break;
    case 3:
        echo "Add a Book";
        echo "<FORM NAME =\"form6\" METHOD =\"POST\" ACTION =\"\">";
        echo "ISBN: <INPUT TYPE = 'TEXT' required Name ='isbn2' maxlength=\"17\"><br>";
        echo "Title: <INPUT TYPE = 'TEXT' Name ='title' maxlength=\"100\"><br>";
        echo "Year: <INPUT TYPE = 'TEXT' Name ='year' maxlength=\"4\"><br>";
        echo "Price: $<INPUT TYPE = 'TEXT' Name ='price' maxlength=\"17\"><br>";;
        echo "Image: <INPUT TYPE='file' Name='image'><br>";
        echo "Quantity: <INPUT TYPE = 'TEXT' required Name ='qty2' maxlength=\"16\">";
        echo "<P align = center>";
        echo "<INPUT TYPE = \"Submit\" Name = \"uq\"  VALUE = \"Update\">";
        echo "</P>";
        echo "</FORM>";
        if (isset($_POST['isbn2']) && isset($_POST['qty2']))
        {
            $inv->addBook($_POST['isbn2'], $_POST['title'], $_POST['year'], $_POST['price'], $_POST['image'], $_POST['qty2']);
            echo "Added " . $_POST['title'] . "to the database";
        }
        break;
    default:
        break;
    }  
}

?>

</body>
</html>
