<?php

/**
 * @date - 17 Nov 2013 
 * @name - COMP DB Drost, Bethel, and Dearing
 * @params - NIL
 * @methods - 
 */
class Order 
{
    private $items = array(); // items in the cart.
    private $con;             // connection
    function __construct() 
    {
        $this->connectToDB("guest", "guestaccount");
    }

    function __destruct() 
    {
        mysqli_close($this->con);
    }


    /**
     * Connects to mysql database.
     * @param user - user name to login to mysql server.
     * @param passwd - password of user to login as.
     */
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


    /**
     * Validates entered student id to a student from database.
     * @uses print_IDForm - displays 
     * @uses validateID - 
     * @param id_number - students id number to use as login / search.
     */
    public function student_id($id_number)
    {
        if ($this->validateID($id_number))
        {
            $_SESSION['id_number'] = $id_number; 
        }
        $this->print_IDForm();
    }

    /** 
     * Once checkout has been selected displays table of items in cart.
     * Displays login dialog if user not logged in.
     * Displays confirm order button if they are logged in.
     */
    public function get_items()
    {
        include 'include/get_items.php';
    }

    /** 
     * Resets items array in order to empty cart items
     */
    public function empty_cart()
    {
        $this->items = array();
    }


    /** 
     * Prints out the number of qty of items contained in the cart
     */
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

    /** 
     * Retrieves total cart cost.
     */
    public function print_cart_total()
    {
        if (empty($this->items))
            return 0;
        else
        {
            $price = 0;
            foreach($this->items as $isbn => $dingo)
            {
                $price += $dingo['qty'] * $dingo['price'];
            }
            return $price;
        }
    }


    /** 
     * Adds book to items array.
     * @param isbn - ISBN of the book to add to the cart.
     * @param title - The title of the book being added.
     * @param price - The price of the book being added.
     * @param qty - the quantity of the book to add.
     */
    public function add_item($isbn, $qty)
    {

        if (array_key_exists($isbn, $this->items)) 
        {
            $this->items[$isbn]["qty"] += $qty;
        } 
        else if ($this->validISBN($isbn) && $qty > 0)
        {
            $query = "SELECT title, price From Book WHERE ";
            $query .= sprintf("isbn = \"%s\" ", $isbn);
            if ($result = mysqli_query($this->con, $query))
            {

                $row = mysqli_fetch_assoc($result);
                mysqli_free_result($result);
                $this->items[$isbn] = array("qty" => $qty,
					"price" => $row['price'], "title" => $row['title']);
            }
        }

    }

    /** 
     * increments the quantity of a book in an order by one.
     * @param isbn - ISBN of the book in items array to increase qty.
     */
    public function increment($isbn)
    {
        if (array_key_exists($isbn, $this->items))
            $this->items[$isbn]["qty"]++;;
    }

    /** 
     * decrements the quantity of a book in an order by one.
     * @param isbn - ISBN of the book in items array to decrease qty.
     */
    public function decrement($isbn)
    {
        if (array_key_exists($isbn, $this->items)
			&& $this->items[$isbn]["qty"] > 1)
            $this->items[$isbn]["qty"]--;
    }   

    /** 
     * Removes an instance of a book in the items array.
     * @param isbn - ISBN of the book in items array to remove.
     */
    public function delete_item($isbn)
    {
        if(array_key_exists($isbn, $this->items))
            unset($this->items[$isbn]);
    } 

    /**
     * Validates entered student id to a student from database.
     * @uses validatePWD - confirms password supplied matches passwd stored. 
     * @uses validateID - confirms student exists in database.
     * @param login_id - students id number to use as login / search.
     * @param login_pwd - students password to validate login.
     */ 
    public function login_id($login_id, $login_pwd)
    {
        if ($this->validateID($login_id) 
            && $this->validatePWD($login_id, $login_pwd))
        {
            $_SESSION['login_id'] = $login_id;
        }
    }

    /** 
     * Validates entered student id to a student from database.
     * confirms student exists in database.
     * @param id_number - students id number to use as login / search.
     */ 
    private function validateID($id_number)
    {
        include 'include/validateID.php';
        return $rtnval;
    }


    /**
     * Validates entered student id to a student from database.
     * @param login_id - students id number to use as login / search.
     * @param login_pwd - students password to validate login.
     */ 
    private function validatePWD($login_id, $login_pwd)
    {
        include 'include/validatePWD.php';
        return $rtn_val;
    }


    /**
     * On a valid logged in student hitting confirm button, function
     * enters the order into Bookorder and Contains tables, and 
     * decrements Stocks appropriately.
     */ 
    public function confirm_order()
    {
        include 'include/confirmOrder.php';
    }

    /**
     * Prints student search field.
     */     
    public function print_IDForm()
    {
        include 'include/print_IDForm.php';
    }

    /**
     * Lists departments available from the database.
     */ 
    public function list_depts()
    {
        include 'include/list_depts.php';
    }


    /**
     *  Lists available courses with books required.
     */ 
    public function list_courses()
    {
        include 'include/list_courses.php';  
    }  

    /**
     * Outputs the course name selected.
     * @uses createForm - 
     */ 
    public function print_course()
    {
        Print "<h2>Courses</h2>\n";

        $this->createForm("",
            array(array('type' => 'hidden', 'name' => 'filter_action',
             'value' => 2),
            array('type' => 'submit', 
            'name' => 'submit', 
            'value' => $_SESSION['dept'] ." ".$_SESSION['course'] . " [x]",
             'class' => 'backl')));
    }

    /**
     * Validates entered student id to a student from database.
     * @uses createForm - 
     */ 
    public function print_author()
    {   
        Print "<h2>Authors</h2>\n";

        $this->createForm("",
            array(array('type' => 'hidden', 'name' => 'author_action',
             'value' => 3),
            array('type' => 'submit', 'name' => 'submit', 
            'value' => $_SESSION['family_name'] . " " .$_SESSION['given_name'] . " [x]",
             'class' => 'backauthor')));

    }

    /**
     * Validates entered student id to a student from database.
     * @param title - title of the book.
     * @param start - the starting character in a set of 3.
     * @param middle - the middle character in a set of 3.
     * @param end - the last character in a set of 3.
     * @param action - the action name to post, either author ot title.
     */ 
    public function list_alpha_choices($title, $start, $middle,
		$end, $action)
    {
        include 'include/list_alpha_choices.php';   
    }

    /** 
     * Lists title characters.
     */   
    public function list_titles()
    {
        include 'include/list_titles.php';
    }

    /** 
     * Prints the final choice letter of titles.
     * @uses createForm -
     */   
    public function list_final()
    {
        Print "<h2>Titles</h2>";

        $this->createForm("",
            array(array('type' => 'hidden', 'name' => 'title_action',
             'value' => 2),
            array('type' => 'submit', 'name' => 'submit', 
            'value' => $_SESSION['talphachar'] . " [x]",
             'class' => 'backl')));
    }

    /** 
     * Lists the Letters available for choice corresponding to 
     * available authors.
     * @uses createForm - 
     */   
    public function list_alpha_authors()
    {
        include 'include/list_alpha_authors.php';
    }

    /** 
     * Lists the available authors acording to selected alpachar.
     * @uses creatForm - displays 
     * @param alphachar - the selected character.
     */   
    public function list_authors($alphachar)
    {
        include 'include/list_authors.php';
    }

    /** 
     * Returns whether the filter should search fo REGEXP, LIKE, or =  
     * @param case - the case value to use. 
     */   
    public function get_filter($case)
    {
        $this_filter = "";
        switch($case)
        {
          case 2:
            $this_filter = sprintf(" REGEXP \"^[%s-%s]\" ", 
				$_SESSION['start'], $_SESSION['end']);
            break;
          case 3:
            $this_filter = sprintf(" LIKE \"%s%%\" ",  
				$_SESSION['alphachar']);
            break;
          case 4:
            $this_filter = sprintf(" =  \"%s\" ", 
				$_SESSION['family_name']);
            $this_filter .= sprintf(" AND given_name = \"%s\" ",
				$_SESSION['given_name']);
            break;
          default:
            break;
        }
        return $this_filter;
    }

    /** 
     * Prints book data out. 
     * @uses get_filter -  
     * @uses createForm - 
     * @param dept - the department selected.
     * @param course - the course selected.
     */   
    public function display_books($dept, $course)
    {
        include 'include/display_books.php';  
    }

    /** 
     * Prints all authors associated with a particular book. 
     * @param isbn - the isbn to search for authors with.
     */   
    private function get_authors($isbn)
    {
        include 'include/get_authors.php';
        return $authors;
    }

    /** 
     * Prints all authors associated with a particular book. 
     * @param form_data- any extra input required in the form.
     * @param input_items The multidimensional array containing 
     * input fields.
     */   
    public function createForm($form_data, $input_items) 
    {
        Print "\n<form action=\"";
        Print htmlspecialchars($_SERVER["PHP_SELF"]) . "\"";
        Print " method=\"post\" " . $form_data . ">\n";
        Print "<fieldset class=\"input\">\n";
        foreach($input_items as $item => $dingo)
        {
            Print "<input type=\"" . $dingo['type'] . "\"";
            switch ($dingo['type'])
            {
            case 'image':
                Print " src=\"" . $dingo['src'] . "\"";
                Print " alt=\"" . $dingo['alt'] . "\"/>\n";
                break;
            case 'hidden':
                // FALL THROUGH
            case 'submit':
                Print " name=\"" . $dingo['name'] . "\"";
                if (isset($dingo['class']))
                    Print " class=\"" . $dingo['class'] . "\"";
                Print " value=\"". $dingo['value'] . "\"/>\n";
                break;
            case 'password':
                // FALL THROUGH
            case 'text':
                Print " name=\"" . $dingo['name'] . "\"";
                Print " required ";
                Print " placeholder=\"" . $dingo['place'] . "\"/>\n"; 
                break;
            default:
                break;
            }
        }

        Print "</fieldset>\n</form>\n";
    }


    /** 
     * Prints all authors associated with a particular book. 
     * @param isbn - the isbn to search for authors with.
     */   
    public function set_store_name()
    {


        $query = sprintf("SELECT * FROM Bookstore WHERE store_id = %s",
			$_SESSION['store']);
        if ($result = mysqli_query($this->con, $query))
        {

            /* fetch associative array */
            while ($row = mysqli_fetch_assoc($result))
            {
                $_SESSION['store_name'] = $row['store_name'];
            }

            /* free result set */
            mysqli_free_result($result);
        }
    }

    /** 
     * Prints all authors associated with a particular book. 
     * @param isbn - the isbn to search for authors with.
     */
    public function sanitize($san_input)
    {
        $san_input = trim($san_input);
        $san_input = stripslashes($san_input);
        $san_input = preg_replace ('/<[^>]*>/', ' ', $san_input);
        $san_input = htmlspecialchars($san_input);
        return $san_input;
    }

    /** 
     * Validates isbn as valid isbn stored in the data base.
     * @param isbn - the isbn to search for authors with.
     * @uses is_valid_isbn.
     */
    private function validISBN($isbn)
    {
        $isbn = $this->sanitize($isbn);

        if (preg_match("/^[0-9]{3}-[0-1]-[0-9\-]+-[0-9a-zA-Z]$/",$isbn))
        {
            if ($this->is_isbn_valid(str_replace('-', '', $isbn)))
            {
                $query = "SELECT COUNT(isbn) From Book WHERE ";
                $query .= sprintf("isbn = \"%s\" ", $isbn);
                if ($result = mysqli_query($this->con, $query))
                {
                    /* fetch associative array */

                    $row = mysqli_fetch_array($result);
                    mysqli_free_result($result);
                    return $row[0] != 0;
                }
            }
        }
        return false;
    }


    /*
     * Checks an ISBN to be valid according to the calculations required 
     * simply every odd number is added to 3 * every even number.
     * Once added together divide by 10. 10 - the remainder == the check
     * digit. but if you add in the check digit to the mix total mod 10 
     * should equal zero. 
     * Code Borrowed from WIKI article about ISBN 13.
     */  
    private function is_isbn_valid($isbn)
    {
        $check = 0;
        for ($i = 0; $i < 13; $i+=2) 
			$check += substr($isbn, $i, 1);
        for ($i = 1; $i < 12; $i+=2) 
			$check += 3 * substr($isbn, $i, 1);
        return $check % 10 == 0;
    }
}
?>
