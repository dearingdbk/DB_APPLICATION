<?php

class InventoryUpdate
{
    private $con;

    function __construct()
    {
	 $this->connectToDB("storeadmin", "adminaccount");
    }

    function __destruct()
    {
        mysqli_close($this->con);
    }

    public function connectToDB($user, $passwd)
    {
        $conn = mysqli_connect("localhost", $user, $passwd, "bookstore");
        if(!$conn)
        {
            die ("Unable to establish connection to the database.");
        }
        else
        {
            $this->con = $conn;
            $this->con->autocommit(FALSE);
            return $this->con;
        }
    }

    public function login_id($login_id, $login_pwd)
    {
        if ($this->validatePWD($login_id, $login_pwd))
        {
            $_SESSION['alogin'] = $login_id;
        }
    }

    private function validatePWD($login_id, $login_pwd)
    {
	    $query = "SELECT COUNT(employee_id) FROM Employee ";
	    $query .= sprintf(" WHERE employee_id = \"%s\" AND password_hash = PASSWORD(\"%s\") ", $login_id, $login_pwd);

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
	    return $rtn_val;
    }

    public function generateReport()
    {
	$query = sprintf("SELECT a.isbn, a.title, c.dept_code, c.course_number, b.quantity from Book a LEFT JOIN Stocks b on a.isbn = b.isbn LEFT JOIN Requires c ON a.isbn = c.isbn");
	if ($result = mysqli_query($this->con, $query))
	{
echo "<table border=1>";
echo "<tr>";
echo "<th>ISBN</th>";
echo "<th>TITLE</th>";
echo "<th>COURSE</th>";
echo "<th>QUANTITY</th>";
echo "</tr>";
	    while ($row = mysqli_fetch_assoc($result))
	    {
echo "<tr>";
		echo "<td>" .$row['isbn'] ."</td><td>". $row['title'] ."</td><td> ". $row['dept_code'] .$row['course_number'] ."</td><td>". $row['quantity'] ."</td>";
echo "<tr>";
	    }
echo "</table>";
	}
else
{
    printf("<br/>Errormessage: %s\n", $this->con->error);
}

    }

    public function update($qty, $isbn)
    {
	$query = sprintf("LOCK TABLES Stocks WRITE; UPDATE Stocks Set quantity = %s WHERE isbn = \"%s\";", $qty, $isbn);

	if (mysqli_multi_query($this->con, $query))
	{
	    do {
		
	    } while (mysqli_next_result($this->con));
	}
	else
            printf("<br/>Errormessage: %s\n", $this->con->error);
	    
        mysqli_query($this->con, "UNLOCK TABLES");
    }

    public function addBook($isbn, $title, $year, $price, $image, $qty)
    {
        $query = sprintf("LOCK TABLES Book WRITE, Stocks WRITE; INSERT INTO Book VALUES(\"%s\", \"%s\", %s, %s, \"%s\"); ", $isbn, $title, $year, $price, $image);
        $query .= sprintf("INSERT INTO Stocks VALUES(\"%s\", 1, %s); ", $isbn, $qty);

        if (mysqli_multi_query($this->con, $query))
        {
            do {

            } while (mysqli_next_result($this->con));
        }
        else
            printf("<br/>Errormessage: %s\n", $this->con->error);

        mysqli_query($this->con, "UNLOCK TABLES");

       // $this->update($qty, $isbn);

    }

}

?>

