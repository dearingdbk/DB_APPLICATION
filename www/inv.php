<?php

class InventoryUpdate
{
    public $con;

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
            $_SESSION['store_id'] = $this->get_store($login_id);
        }
    }

    private function validatePWD($login_id, $login_pwd)
    {
        $query = "SELECT COUNT(employee_id) FROM Employee ";
        $query .= sprintf(" WHERE employee_id = \"%s\" AND ", $login_id);
        $query .= sprintf("password_hash = PASSWORD(\"%s\") ", $login_pwd);

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

    private function get_store($employee_id)
    {
        $query = "SELECT store_id FROM EmployeeList WHERE ";
        $query .= sprintf("employee_id = \"%s\" ", $employee_id);
        if ($result = mysqli_query($this->con, $query))
        {   
            $row = mysqli_fetch_assoc($result);
            if (intval($row['store_id']))
                return $row['store_id'];
            else
                return 1;
        }
    }
        public function generateReport()
        {
            $query = "SELECT a.isbn, a.title, c.dept_code, c.course_number, ";
            $query .= "b.quantity from Book a INNER JOIN Stocks b on ";
            $query .= "a.isbn = b.isbn LEFT JOIN Requires c ON a.isbn = c.isbn ";
            $query .= sprintf(" WHERE b.store_id = %s ", $_SESSION['store_id']);
            $query .= "ORDER BY a.title";
            if ($result = mysqli_query($this->con, $query))
            {
                echo "<table border=1>\n";
                echo "<tr>\n";
                echo "<th>ISBN</th>\n";
                echo "<th>TITLE</th>\n";
                echo "<th>COURSE</th>\n";
                echo "<th>QUANTITY</th>\n";
                echo "</tr>\n";
                while ($row = mysqli_fetch_assoc($result))
                {
                    echo "<tr>\n";
                    echo "\t<td>" .$row['isbn'] ."</td>\n\t<td>". $row['title'] ."</td>\n\t<td> ". $row['dept_code'] .$row['course_number'] ."</td>\n\t<td>". $row['quantity'] ."</td>\n";
                    echo "</tr>\n";
                }
                echo "</table>\n";
            }
            else
            {
                printf("<br/>Errormessage: %s\n", $this->con->error);
            }

        }

        public function update($qty, $isbn)
        {
            $qqq = "";
            $blah = sprintf("SELECT quantity FROM Stocks WHERE isbn = \"%s\";", $isbn);
            if ($res = mysqli_query($this->con, $blah))
            {
                while ($row = mysqli_fetch_assoc($res))
                    $qqq = $row['quantity'];
            }

            $query = sprintf("LOCK TABLES Stocks WRITE; UPDATE Stocks Set quantity = %s WHERE isbn = \"%s\";", $qty += $qqq, $isbn);

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
            if (preg_match("/^[0-9]{3}-[0-1]-[0-9\-]+-[0-9a-zA-Z]$/",$isbn))
            {
                if ($this->is_isbn_valid(str_replace('-', '', $isbn)))
                {

                    $query = "LOCK TABLES Book WRITE, Stocks WRITE; INSERT INTO Book "; 
                    $query .= sprintf(" VALUES(\"%s\", \"%s\", %s, %s, \"%s\"); ", 
                        $isbn, $title, $year, $price, $image);
                    $query .= sprintf(" INSERT INTO Stocks VALUES(\"%s\", %s, %s); ",
                        $isbn,$_SESSION['store_id'], $qty);

                    if (mysqli_multi_query($this->con, $query))
                    {
                        $rtn_val = true;
                        mysqli_next_result($this->con); //skip lock;
                        if (mysqli_affected_rows($this->con) <= 0)
                            $rtn_val = false;
                        mysqli_next_result($this->con); // next insert
                        if (mysqli_affected_rows($this->con) <= 0)
                            $rtn_val = false;
                        mysqli_next_result($this->con);
                    }
                    else
                    {
                        printf("<br/>Errormessage: %s\n", $this->con->error);
                        $rtn_val = false;

                    }
                }
                else
                    printf("Invalid ISBN entered.\n");
            }
            else
                printf("That does not look like an isbn.\n");
            
            if ($rtn_val)
            {
                mysqli_query($this->con, "UNLOCK TABLES");
                mysqli_commit($this->con);
                echo "Added " . $title . " to the database.";
            }
            else
            {
                mysqli_rollback($this->con);
                mysqli_query($this->con, "ROLLBACK");
                mysqli_query($this->con, "UNLOCK TABLES");
                echo "Failed to enter ". $title . " to the database.";
            }

        }

        public function checkOrder($stunum)
        {
            $query = sprintf("SELECT * FROM order_view WHERE id_number = %s", $stunum);

            if ($result = mysqli_query($this->con, $query))
            {   
                echo "Student: " .$stunum; 
                echo "<FORM name='n' method=\"POST\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\">\n";
                echo "<table border=1>\n";
                echo "<tr>\n";
                echo "<th>ORDER</th>\n";
                echo "<th>DATE</th>\n";
                echo "<th>ISBN</th>\n";
                echo "<th>TITLE</th>\n";
                echo "<th>PRICE</th>\n";
                echo "<th>RECEIVED</th>\n";
                echo "</tr>\n";
                while ($row = mysqli_fetch_assoc($result))
                {
                    echo "<tr>\n";
                    echo "\t<td>" .$row['order_id'] ."</td>\n";
                    echo "\t<td>". $row['order_date'] ."</td>\n";
                    echo "\t<td>". $row['isbn'] ."</td>\n";
                    echo "\t<td>". $row['title'] ."</td>\n";
                    echo "\t<td>". $row['price']/100 ."</td>\n";

                    echo "\t<td>\n";
                    echo "<input type=\"hidden\" name=\"order_id\" value=\"". $row['order_id'] . "\"/>";
                    echo "<input type='checkbox' name='rec[]' value='".$row['isbn']."+".$row['order_id']."'";
                    if ($row['received'] == 1) echo "checked='checked'";
                    echo ">";
                    echo "\t</td>\n";

                    echo "</tr>\n";
                }
                echo "</table>\n";
                echo "<INPUT TYPE = 'Submit' name='radio' value='Update'>\n";
                echo "</FORM>\n";
            }
            else
            {
                echo "Invalid Student ID";
            }

        }
        public function printOrders()
        {
            $query = sprintf("SELECT order_id, order_date, id_number, isbn, title, price, received FROM order_view");

            if ($result = mysqli_query($this->con, $query))
            {
                echo "<FORM name='n' method=\"POST\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\">\n";
                echo "<table border=1>\n";
                echo "<tr>\n";
                echo "\t<th>ORDER</th>\n";
                echo "\t<th>DATE</th>\n";
                echo "\t<th>STUDENT</th>\n";
                echo "\t<th>ISBN</th>\n";
                echo "\t<th>TITLE</th>\n";
                echo "\t<th>PRICE</th>\n";
                echo "\t<th>RECEIVED</th>\n";
                echo "</tr>\n";
                while ($row = mysqli_fetch_assoc($result))
                {
                    echo "<tr>\n";
                    echo "\t<td>" .$row['order_id'] ."</td>\n";
                    echo "\t<td>". $row['order_date'] ."</td>\n";
                    echo "\t<td>". $row['id_number'] ."</td>\n";
                    echo "\t<td>". $row['isbn'] ."</td>\n";
                    echo "\t<td>". $row['title'] ."</td>\n";
                    echo "\t<td>". $row['price']/100 ."</td>\n";

                    echo "\t<td>\n";
                    echo "<input type='checkbox' name='rec[]' value='".$row['isbn']."+".$row['order_id']."'";
                    if ($row['received'] == 1) echo "checked='checked'";
                    echo ">";

                    echo "</td>\n";
                    echo "</tr>\n";
                }
                echo "</table>\n";
                echo "<INPUT TYPE = 'Submit' name='radio' value='Update'>\n";
                echo "</FORM>\n";

            }
            else
            {
                printf("<br/>Errormessage: %s\n", $this->con->error);
            }
        }


        /*
         * @ WIKI CODE
         *
         */
        private function is_isbn_valid($n)
        {
            $check = 0;
            for ($i = 0; $i < 13; $i+=2) $check += substr($n, $i, 1);
            for ($i = 1; $i < 12; $i+=2) $check += 3 * substr($n, $i, 1);
            return $check % 10 == 0;
        }
}
?>

