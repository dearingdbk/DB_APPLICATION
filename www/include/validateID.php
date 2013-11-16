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
            $_SESSION['id_number'] = $id_number;
            $rtn_val = true;
        }
        else
            $rtn_val = false;
    }
    else
    {
        echo "The selected validate failed on execution.";
        printf("<br/>Errormessage: %s\n", $this->con->error);
    }
}
else
    return false;

return $rtn_val;
?>
