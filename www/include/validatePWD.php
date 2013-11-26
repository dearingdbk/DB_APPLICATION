<?php
$login_pwd = trim($login_pwd);
$login_pwd = stripslashes($login_pwd);
$login_pwd = htmlspecialchars($login_pwd);
$login_id = trim($login_id);
$login_id = stripslashes($login_id);
$login_id = htmlspecialchars($login_id);

$query = "SELECT COUNT(id_number) FROM student_id WHERE ";
$query .= sprintf("id_number = \"%s\" AND password_hash ", $login_id);
$query .= sprintf("= PASSWORD(\"%s\") ", $login_pwd);

if ($result = mysqli_query($this->con, $query))
{
    $row = mysqli_fetch_array($result);
    if (intval($row[0]))
    {
        $rtn_val = true;
    }
    else
        $rtn_val = false;
}   
else
    $rtn_val = false;
?>
