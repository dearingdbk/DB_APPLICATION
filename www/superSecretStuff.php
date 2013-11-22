<?php require('inventoryUpdate.php'); ?>
<?php session_start(); ?>
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
else
{
//echo "AWEGWEgWEG";
    //$inv->generateReport();
//$inv->update(111, "978-0-04-943050-1");
    $inv->addBook("978-04-39-02348-1", "Hunger Games", 2008, 10, images/image.png, 6);
    $inv->generateReport();
}
?>
</head>
<body>

</body>
</html>
