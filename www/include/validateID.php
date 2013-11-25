<?php
$id_number = trim($id_number);
$id_number = stripslashes($id_number);
$id_number = htmlspecialchars($id_number);

if (preg_match("/1[0-9]{8}/i", $id_number))
{
    $query = sprintf("SELECT COUNT(id_number) FROM student_id WHERE id_number = %s ", $id_number);
    $query = mysqli_real_escape_string($this->con, $query);
    if ($result = mysqli_query($this->con, $query))
    {
        $row = mysqli_fetch_array($result);
        if (intval($row[0]))
        {
            $rtnval = true;
        }
        else
            $rtnval = false;
    }
    else
    {
        $rtn_val = false;
    }
}
else
    $rtnval =  false;
?>
