<?php

mysql_connect("localhost", "guest", "guestaccount") or die(mysql_error());
mysql_select_db("bookstore") or die(mysql_error());
$data = mysql_query("SELECT * FROM Book")
or die(mysql_error());
while($info = mysql_fetch_array( $data ))
{
	Print "<div id=\"item_box\">";
	Print "<img src=\"" .$info['image_url'] . "\" alt=\"book_image\">";
	Print "<h2 class=\"title\">" .$info['title'] . "</h2>";
	Print "<p class=\"isbn\">" .$info['isbn'] . "</p>";
	Print "<p class=\"price\">" .$info['price'] . "</p>";
	Print "</div>";
}
?>
