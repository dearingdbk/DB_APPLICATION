        
<?php
$login_pwd = trim($login_pwd);
$login_pwd = stripslashes($login_pwd);
$login_pwd = htmlspecialchars($login_pwd);
$login_id = trim($login_id);
$login_id = stripslashes($login_id);
$login_id = htmlspecialchars($login_id);

$query = "SELECT id_number, password_hash FROM student_id ";
$query .= sprintf(" WHERE id_number = \"%s\" AND password = PASSWORD(\"%s\") ", $login_id, $login_pwd);
$query = mysqli_real_escape_string($this->con, $query);

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
