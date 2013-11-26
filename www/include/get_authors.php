<?php        

$authors = "";
$query = "SELECT family_name, given_name FROM Written ";
$query .= sprintf("WHERE isbn = \"%s\" ", $isbn);
if ($result = mysqli_query($this->con, $query))
{
    while ($row = mysqli_fetch_assoc($result))
    {
        $authors .= " " . $row['family_name'];
        $authors .= " " . $row['given_name'];
        $authors .= " | ";
    }
}
else
{   
    echo "The selected query failed on execution.\n";
    printf("<br/>Errormessage: %s\n", $this->con->error);
}

?>
