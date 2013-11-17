<?php


/**
 * @author - 
 * @date - 
 * @name - 
 * @params - 
 * @methods - 
 */
class Order 
{
    private $items = array();
    private $con;
    function __construct() 
    {
        $this->connectToDB("guest", "guestaccount");
    }

    function __destruct() 
    {
        mysqli_close($this->con);
    }


    public function student_id($id_number)
    {
        if ($this->validateID($id_number))
        {
            $_SESSION['id_number'] = $id_number; 
        }
        $this->print_IDForm();

    }


    public function get_items()
    {
        include 'include/get_items.php';
    }

    public function empty_cart()
    {
        $this->items = array();
    }

    public function print_cart_qty()
    {
        if (empty($this->items))
            return "";
        else
        {
            $num = 0;
            foreach($this->items as $isbn => $dingo)
            {
                $num += $dingo['qty'];
            }
            return "<h2 id=\"items\">(" . $num . ")</h2>";
        }
    }

    public function add_item($isbn, $title, $price, $qty)
    {

        if (array_key_exists($isbn, $this->items)) 
        {

            $this->items[$isbn]["qty"] += $qty;
        } 
        else
        {
            $this->items[$isbn] = array("qty" => $qty, "price" => $price, "title" => $title);
        } 
    }

    public function delete_item($isbn)
    {
        if(array_key_exists($isbn, $this->items))
            unset($this->items[$isbn]);
    } 

    public function login_id($login_id, $login_pwd)
    {
        if ($this->validateID($login_id) 
            && $this->validatePWD($login_id, $login_pwd))
        {
            $_SESSION['login_id'] = $login_id;
        }
    }

    private function validateID($id_number)
    {
        include 'include/validateID.php';
        return $rtnval;
    }

    private function validatePWD($login_id, $login_pwd)
    {
        $login_pwd = trim($login_pwd);
        $login_pwd = stripslashes($login_pwd);
        $login_pwd = htmlspecialchars($login_pwd);
        $login_id = trim($login_id);
        $login_id = stripslashes($login_id);
        $login_id = htmlspecialchars($login_id);
        $query = "SELECT COUNT(id_number) FROM student_id ";
        $query .= sprintf(" WHERE id_number = \"%s\" AND password_hash = PASSWORD('%s') ", $login_id, $login_pwd);

        if ($result = mysqli_query($this->con, $query))
        {
            $row = mysqli_fetch_array($result);
            if (intval($row[0]))
            {
                $rtn_val = true;
            }
            else
            {
                $rtn_val = false;
            }
        }
        else
        {
            $rtn_val = false;
        }
        return $rtn_val;
    }

    public function confirm_order()
    {
        $insert = "INSERT INTO Bookorder (order_date, id_number) ";
        $insert .= sprintf(" VALUES(\"%s\", \"%s\") ", date("Y-m-d"), $_SESSION['login_id']);
        if ($result = mysqli_query($this->con, $insert))
        {
            mysqli_free_result($result);
            $rtn_val = true; 
            $order_id = mysqli_insert_id($this->con);

            mysqli_commit($this->con); // Need to commit to ensure constraints are met.
            foreach($this->items as $isbn => $dingo)
            {  
                $query = "SELECT quantity FROM Stocks WHERE ";
                $query .= sprintf(" isbn = \"%s\" AND store_id = \"%s\" ", $isbn, $_SESSION['store']);

                if ($result = mysqli_query($this->con, $query))
                {

                    $row = mysqli_fetch_assoc($result);
                    if ($row['quantity'] >= $dingo['qty'])
                    {
                        if ($this->update_qty($isbn, $dingo['qty']))
                        {
                            $insert = "INSERT INTO Contains VALUES ";
                            $insert .= sprintf(" ( \"%s\", \"%s\", 0, \"%s\") ", $isbn, $order_id, $dingo['qty']); 
                            if(!mysqli_query($this->con, $insert))
                            {
                                $rtn_val = false;
                                printf("<br/>Errormessage: %s %s\n",$order_id, $this->con->error);
                                break;
                            }
                        }
                        else
                        {
                            $rtn_val = false;
                            break;
                        }
                    }
                    else
                    {
                        $rtn_val = false;
                        echo "NOT ENOUGH IN STOCK";
                        break;
                    }

                    mysqli_free_result($result);
                }
                else
                {
                    $rtn_val = false;
                    break;
                }
            }
        }
        else
        {
            $rtn_val = false;
            printf("<br/>Errormessage: %s\n", $this->con->error);
        }
        if ($rtn_val)
        {
            mysqli_commit($this->con);
            $_SESSION['confirmation'] = $order_id;
            $this->empty_cart();

        }
        else
        {
            printf("<br/>Errormessage: %s %s\n", $order_id, $this->con->error);
            echo "<h2> SUPER". $rtn_val . "</h2>";
            mysqli_rollback($this->con);
        }
    }

    private function update_qty($isbn, $qty)
    {
        $update = "UPDATE Stocks SET quantity = ";
        $update .= sprintf("quantity - \"%s\" WHERE isbn = \"%s\" ", $qty, $isbn);
        $this->connectToDB("storeadmin", "adminaccount");
        if(mysqli_query($this->con, $update))
        {
            $rtn_val = true;
        }
        else
        {
            printf("<br/>Errormessage: %s\n", $this->con->error);
            $rtn_val = false;
        }

        $this->connectToDB("guest", "guestaccount");
        return $rtn_val;
    }

    public function print_IDForm()
    {
        include 'include/print_IDForm.php';
    }

    public function list_depts()
    {
        include 'include/list_depts.php';
    }

    public function list_courses()
    {
        include 'include/list_courses.php';  
    }  

    public function print_course()
    {
        Print "<h2>Courses</h2>\n";
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"filter_action\" value=\"2\"/>";
        Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $_SESSION['dept'] . $_SESSION['course'] . " [x]\"/>";
        Print "</fieldset>";
        Print "</form>";

    }

    public function print_author()
    {   
        Print "<h2>Authors</h2>\n";
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"author_action\" value=\"3\"/>";
        Print "<input type=\"submit\" class=\"backauthor\" name=\"submit\" value=\"" . $_SESSION['family_name'] .", " . $_SESSION['given_name'] . " [x]\"/>";
        Print "</fieldset>";
        Print "</form>";

    }



    public function list_alpha_choices($title, $start, $middle, $end, $action)
    {
        include 'include/list_alpha_choices.php';   
    }


    public function list_titles()
    {
        include 'include/list_titles.php';
    }

    public function list_final()
    {
        Print "<h2>Titles</h2>";
        Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
        Print "<fieldset class=\"input\">";
        Print "<input type=\"hidden\" name=\"title_action\" value=\"2\"/>";
        Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $_SESSION['talphachar'] . " [x]\"/>";
        Print "</fieldset>";
        Print "</form>";

    }


    public function list_alpha_authors()
    {
        include 'include/list_alpha_authors.php';
    }


    public function list_authors($alphachar)
    {
        include 'include/list_authors.php';
    }

    public function get_filter($case)
    {
        $this_filter = "";
        switch($case)
        {
        case 2:
            $this_filter = sprintf(" REGEXP \"^[%s-%s]\" ", $_SESSION['start'], $_SESSION['end']);
            break;
        case 3:
            $this_filter = sprintf(" LIKE \"%s%%\" ",  $_SESSION['alphachar']);
            break;
        case 4:
            $this_filter = sprintf(" =  \"%s\" ", $_SESSION['family_name']);
            $this_filter .= sprintf(" AND given_name = \"%s\" ",$_SESSION['given_name']);
            break;
        default:
            break;
        }
        return $this_filter;
    }

    public function display_books($dept, $course)
    {
        include 'include/display_books.php';  
    }


    private function get_authors($isbn)
    {
        include 'include/get_authors.php';
        return $authors;
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
}
?>
