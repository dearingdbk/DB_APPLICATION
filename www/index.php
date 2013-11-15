<?php require("order.php"); ?>
<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
$store = "1";

$mysq = mysqli_connect("localhost", "guest", "guestaccount", "bookstore");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$query = sprintf("SELECT * FROM Bookstore WHERE store_id = %s", $store);
if ($result = mysqli_query($mysq, $query)) 
{

    /* fetch associative array */
    while ($row = mysqli_fetch_assoc($result))
    {
        $storename = $row['store_name'];
    }

    /* free result set */
    mysqli_free_result($result);
}

?>
<title><?php echo $storename; ?></title>

<link rel="stylesheet" type="text/css" href="style.css" />
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="style-ie.css" />
<![endif]-->
	<script type="text/javascript">
<!-- function showItems(str)
{
	if (str.user=="")
	{
		document.getElementById("scroll").innerHTML="";
		return;
	}
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		   document.getElementById("scroll").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","getItems.php?user=" + str.user + "&myquery=" + str.my_query,true);
	xmlhttp.send();
} -->


</script>

</head>
<body>
<?php

/*if(!$cart && !isset($_SESSION['cart_action']) && !isset($_POST['cart_action']))*/
if(!$cart)
{
    $_SESSION['cart'] = new order();

    $cart = $_SESSION['cart'];
}
?>

<div id="page-wrap">
<div id="inside">

<div id="header">
<?php 
Print "<h2 id=\"store\">" . $storename . "</h2>";
?>
</div>

<div id="left-sidebar">
<?php
echo "<div id=\"student\">";
if (isset($_POST['drop_id']))
        unset($_SESSION['id_number']);

if (isset($_POST['id_number']))
    $cart->student_id($_POST['id_number']);
else
    $cart->print_IDForm();


echo "</div><div id=\"department\">";

if (isset($_POST['given_name']))
    $_SESSION['given_name'] = $_POST['given_name'];

if (isset($_POST['family_name']))
        $_SESSION['family_name'] = $_POST['family_name'];

if (isset($_POST['dept']))
    $_SESSION['dept'] = $_POST['dept'];

if (isset($_POST['course']))
    $_SESSION['course'] = $_POST['course'];

if (isset($_POST['start']))
    $_SESSION['start'] = $_POST['start'];

if (isset($_POST['middle']))
    $_SESSION['middle'] = $_POST['middle'];

if (isset($_POST['end']))
    $_SESSION['end'] = $_POST['end'];

if (isset($_POST['tstart']))
        $_SESSION['tstart'] = $_POST['tstart'];

if (isset($_POST['tmiddle']))
        $_SESSION['tmiddle'] = $_POST['tmiddle'];

if (isset($_POST['tend']))
        $_SESSION['tend'] = $_POST['tend'];


if (isset($_POST['alphachar']))
    $_SESSION['alphachar'] = $_POST['alphachar'];

if (isset($_POST['talphachar']))
        $_SESSION['talphachar'] = $_POST['talphachar'];

if (isset($_POST['filter_action']))
    $_SESSION['filter_action'] = $_POST['filter_action'];
elseif (!isset($_SESSION['filter_action']))
    $_SESSION['filter_action'] = 1;

switch($_SESSION['filter_action'])
{
    case 1:
        $cart->list_depts();
        break;
    case 2:
        $cart->list_courses();
        break;
    case 3:
        $cart->print_course();
        break;
    default:
        break;
}

echo "</div><div id=\"author\">";

if (isset($_POST['author_action']))
            $_SESSION['author_action'] = $_POST['author_action'];
    elseif (!isset($_SESSION['author_action']))
            $_SESSION['author_action'] = 1;

switch($_SESSION['author_action'])
    {
case 1:
    $cart->list_alpha_choices("Authors", "start", "middle", "end", "author");
    break;
case 2:
    $cart->list_alpha_authors($_SESSION['start'], $_SESSION['middle'], $_SESSION['end']);
    break;
case 3:
    $cart->list_authors($_SESSION['alphachar']);
    break;
case 4:
    $cart->print_author();
    break;
default:
    break;
    }
echo "</div>";

if (isset($_POST['title_action']))
        $_SESSION['title_action'] = $_POST['title_action'];
    elseif (!isset($_SESSION['title_action']))
        $_SESSION['title_action'] = 1;

switch($_SESSION['title_action'])
{
case 1:
    $cart->list_alpha_choices("Titles", "tstart", "tmiddle", "tend", "title");
    break;
case 2:
    $cart->list_titles();
    break;
case 3:
    $cart->list_final();
    break;
default:
    break;
}
?>

</div>

<div id="main-content">


<div id="scroll">
<?php

$cart->display_books();
/*
switch($_SESSION['filter_action'])
{       
case 1:
    $cart->display_books();
    break;
case 2:
    $cart->display_books($_SESSION['dept']);
    break;
case 3:
    $cart->display_books($_SESSION['dept'],$_SESSION['course']);
    break;
default:
    break;
}*/

?>
</div>


<br /><br />

<p>
Litterarum vero at tincidunt adipiscing vel. Dolore quod in lectores littera iis. Dolor lobortis sequitur nobis per soluta. Dignissim fiant diam option facer decima. Facilisis est in erat ullamcorper eodem. Mirum veniam quam luptatum ut anteposuerit. Commodo luptatum qui parum humanitatis lobortis. Molestie feugiat hendrerit dolor nam sed. Insitam feugait te wisi doming quam. Nostrud formas in augue dolore sit. 
</p>

<p>
Hendrerit lectorum et notare legentis nulla. Habent clari commodo claram mazim magna. Vulputate nihil Investigationes sequitur humanitatis claritatem. Assum exerci molestie nobis feugait eodem. Aliquam delenit cum sed me veniam. Nunc eodem facilisi iis iriure commodo. Tempor typi illum velit consuetudium zzril. Tation liber claritas minim iis nobis. Claritatem placerat delenit iusto iis facilisis. Veniam tempor dolore congue mazim esse. 
</p>

<p>
In possim luptatum seacula est claram. Legere molestie quinta nibh erat ut. Vel feugait nostrud commodo esse delenit. Amet elit lectorum dolor vel blandit. Velit qui est tation legere at. Notare tincidunt te dynamicus in legere. Liber typi dynamicus legunt nulla est. Nunc option quod est formas legere. Dynamicus accumsan mutationem quinta in iis. Quis quam facilisis iusto eodem possim. 
</p>

<p>
Ut in dignissim iriure dolore feugiat. Claritas ut non anteposuerit te vero. Et facit amet at vero sequitur. Eros exerci non et ut facilisis. Suscipit consectetuer accumsan quam nonummy illum. Ullamcorper ea legunt volutpat me consuetudium. Qui littera nonummy delenit modo eorum. Facilisi hendrerit et typi lorem non. Tempor doming in iriure facit eleifend. Ii magna consectetuer consuetudium qui adipiscing. 
</p>

<p>
Videntur wisi dolore parum quinta in. Te in aliquip nihil dynamicus gothica. Nunc possim legunt molestie modo wisi. In zzril vero zzril dolore augue. Facilisi lectores nihil exerci doming demonstraverunt. Typi qui sequitur notare modo magna. Accumsan facer Investigationes qui eum fiant. Me habent cum est eu feugait.
</p>
</div>

<div style="clear: both;"></div>

<div id="footer">
<p>Footer stuff.</p>
</div>

</div>

<div style="clear: both;"></div>

</div>

</body>

</html>

