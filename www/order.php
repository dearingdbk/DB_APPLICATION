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
        $this->con = $this->connectToDB('guest', 'guestaccount');
        $this->con->autocommit(FALSE);
    }

    public function student_id($id_number)
    {
        if ($this->validateID($id_number))
        {
            $_SESSION['id_number'] = $id_number; 
        }
        $this->print_IDForm();

    }

    private function validateID($id_number)
    {
        $id_number = trim($id_number);
        $id_number = stripslashes($id_number);
        $id_number = htmlspecialchars($id_number);
        if (preg_match("/1[0-9]{8}/i", $id_number))
        {
            $admin_con = $this->connectToDB('storeadmin', 'adminaccount');
            $query = sprintf("SELECT COUNT(id_number) FROM Student WHERE id_number = %s ", $id_number);
            $query = mysqli_real_escape_string($admin_con, $query);
            if ($result = mysqli_query($admin_con, $query))
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
                echo "The selected query failed on execution.";
        }
        else
            return false;

        if (isset($admin_con))
            $admin_con->close();
        return $rtn_val;
    }

    public function print_IDForm()
    {
        if (isset($_SESSION['id_number']))
        {
            Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
            Print "<fieldset class=\"input\">";
            Print "<input type=\"hidden\" name=\"drop_id\" value=\"3\"/>";
            Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $_SESSION['id_number'] . " [x]\"/>";
            Print "</fieldset>";
            Print "</form>";
        }
        else
        {
            Print "<h2>Student ID</h2>\n";
            Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
            Print "<fieldset class=\"input\">";
            Print "<input type=\"text\" name=\"id_number\" />";
            Print "<input type=\"submit\"  name=\"submit\" value=\"submit\"/>";
            Print "</fieldset>";
            Print "</form>";
        }
    }
    public function list_depts()
    {
        Print "<h2>Departments</h2>\n";
        $query = sprintf("SELECT DISTINCT(dept_code) FROM Requires ORDER BY dept_code");
        if ($result = mysqli_query($this->con, $query))
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                Print "<form method=\"post\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\">\n";
                Print "<fieldset class=\"input\">";
                Print "<input type=\"hidden\" name=\"dept\" value=\"" . $row['dept_code'] ."\"/>\n";
                Print "<input type=\"hidden\" name=\"course\" value=\"" . $row['course_number'] . "\"/>\n";
                Print "<input type=\"submit\" class=\"link\" name=\"COMP\" value=\"" . $row['dept_code'] . "\"/>\n";
                Print "<input type=\"hidden\" name=\"filter_action\" value=\"2\"/>\n";
                Print "</fieldset>";
                Print "</form>\n";
            }
        }
    }

    public function list_courses()
    {  
        Print "<h2>Courses</h2>\n"; 
        $query = "SELECT * FROM Requires WHERE ";
        $query .= sprintf("dept_code = \"%s\" ", $_SESSION['dept']);
        $query .= "ORDER BY course_number";
        if ($result = mysqli_query($this->con, $query))
        { 
            Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">\n";
            Print "<fieldset class=\"input\">";
            Print "<input type=\"hidden\" name=\"filter_action\" value=\"1\"/>\n";
            Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $_SESSION['dept'] . " [x]\"/>\n"; 
            Print "</fieldset>";
            Print "</form>\n";
            while ($row = mysqli_fetch_assoc($result))
            {
                Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">\n";
                Print "<fieldset class=\"input\">"; 
                Print "<input type=\"hidden\" name=\"dept\" value=\"" . $row['dept_code'] ."\"/>\n";
                Print "<input type=\"hidden\" name=\"course\" value=\"" . $row['course_number'] . "\"/>\n";
                Print "<input type=\"submit\" class=\"link\" name=\"submit\" value=\"" . $row['dept_code'] ." [" . $row['course_number'] . "] [" . $row['section_code'] . $row['term_number'] . "]\"/>\n";
                Print "<input type=\"hidden\" name=\"filter_action\" value=\"3\"/>\n";
                Print "</fieldset>";
                Print "</form>\n";
            }   
        }
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
        Print "<h2>" . $title . "</h2>";
        $str = $alpha ='A';
        while (strlen($str) <= 1)
        {   
            Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">\n";
            Print "<fieldset class=\"input\">\n";
            Print "<input type=\"hidden\" name=\"" . $start;
            Print "\" value=\"". (strlen($str) > 1 ? $str : $str++) . "\"/>\n";
            Print "<input type=\"hidden\" name=\"" . $middle;
            Print "\" value=\"" . (strlen($str) > 1 ? $str : $str++) . "\"/>\n";
            if (strlen($str) > 1)
                $str = 'Z';
            Print "<input type=\"submit\" class=\"link\" name=\"COMP\" value=\"" . $alpha . " - " . $str . "\"/>\n";
            Print "<input type=\"hidden\" name=\"" . $end . "\" value=\"" . $str++ . "\"/>\n";
            Print "<input type=\"hidden\" name=\"" .$action ."_action\" value=\"2\"/>\n";
            Print "</fieldset>\n";
            Print "</form>\n";
            $alpha = $str;
        }
    }


    public function list_titles()
    {
        Print "<h2>Titles</h2>";
        $query =  "SELECT DISTINCT(SUBSTRING(title FROM 1 FOR 1)) ";
        $query .= "FROM all_book_data  WHERE title ";
        $query .= sprintf("REGEXP \"^[%s-%s]\" ", $_SESSION['tstart'], $_SESSION['tend']);
        $query .= " ORDER BY title ";
        if ($result = mysqli_query($this->con, $query))
        {
            Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
            Print "<fieldset class=\"input\">";
            Print "<input type=\"hidden\" name=\"title_action\" value=\"1\"/>";
            Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $_SESSION['tstart'] ." - " .        $_SESSION['tend'] . " [x]\"/>";
            Print "</fieldset>";
            Print "</form>";
            while ($row = mysqli_fetch_array($result))
            {
                Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
                Print "<fieldset class=\"input\">";
                Print "<input type=\"hidden\" name=\"talphachar\" value=\"" . $row[0] ."\">";
                Print "<input type=\"submit\" class=\"link\" name=\"submit\" value=\"" . $row[0] . "\">";
                Print "<input type=\"hidden\" name=\"title_action\" value=\"3\">\n";
                Print "</fieldset>";
                Print "</form>";
            }
        }
        else
            echo "The selected query failed on execution.";

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
        Print "<h2>Authors</h2>";
        switch($_SESSION['filter_action'])
        {
        case 1:
            $query =  "SELECT DISTINCT(SUBSTRING(family_name FROM 1 FOR 1)) ";
            $query .= "FROM Author WHERE family_name ";
            $query .= sprintf("REGEXP \"^[%s-%s]\" ", $_SESSION['start'], $_SESSION['end']);
            break;
        case 2:
            $query =  "SELECT DISTINCT(SUBSTRING(a.family_name FROM 1 FOR 1)) ";
            $query .= "FROM Written a INNER JOIN Requires b ON a.isbn = b.isbn ";
            $query .= sprintf("WHERE b.dept_code = \"%s\" ", $_SESSION['dept']);
            $query .= sprintf("AND family_name REGEXP \"^[%s-%s]\" ", $_SESSION['start'], $_SESSION['end']);
            break;
        case 3:
            $query = "SELECT DISTINCT(SUBSTRING(a.family_name FROM 1 FOR 1)) ";
            $query .= "FROM Written a INNER JOIN Requires b ON a.isbn = b.isbn ";
            $query .= sprintf("WHERE b.dept_code = \"%s\" ", $_SESSION['dept']);
            $query .= sprintf("AND b.course_number = \"%s\" ", $_SESSION['course']);
            $query .= sprintf("AND family_name REGEXP \"^[%s-%s]\" ", $_SESSION['start'], $_SESSION['end']);
            break;
        default:
            $query =  "SELECT DISTINCT(SUBSTRING(family_name FROM 1 FOR 1)) ";
            $query .= "FROM Author WHERE family_name ";
            $query .= sprintf("REGEXP \"^[%s-%s]\" ", $_SESSION['start'], $_SESSION['end']);
            break;

        }
        if ($result = mysqli_query($this->con, $query))
        {
            Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
            Print "<fieldset class=\"input\">";
            Print "<input type=\"hidden\" name=\"author_action\" value=\"1\"/>";
            Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $_SESSION['start'] ." - " . $_SESSION['end'] . " [x]\"/>";
            Print "</fieldset>";
            Print "</form>";
            while ($row = mysqli_fetch_array($result))
            {
                Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
                Print "<fieldset class=\"input\">";
                Print "<input type=\"hidden\" name=\"alphachar\" value=\"" . $row[0] ."\">";
                Print "<input type=\"submit\" class=\"link\" name=\"submit\" value=\"" . $row[0] . "\">";
                Print "<input type=\"hidden\" name=\"author_action\" value=\"3\">\n";
                Print "</fieldset>";
                Print "</form>";
            }
        }
        else
            echo "The selected query failed on execution.";

    }


    public function list_authors($alphachar)
    {
        Print "<h2>Authors</h2>";

        $query = "SELECT DISTINCT(family_name), given_name  FROM all_book_data ";
        $title_act = "";
        switch ($_SESSION['title_action'])
        {
        case 2:
            $title_act = sprintf(" AND title REGEXP \"^[%s-%s]\" ",$_SESSION['tstart'], $_SESSION['tend']);
            break;
        case 3:
            $title_act = sprintf(" AND title LIKE \"%s%%\" ",$_SESSION['talphachar']);
            break;
        default:
            break;

        }
        switch($_SESSION['filter_action'])
        {
        case 2:
            $query .= " WHERE ";
            $query .= sprintf("dept_code = \"%s\" ", $_SESSION['dept']);
            $query .= sprintf("AND family_name LIKE \"%s%%\" ", $_SESSION['alphachar']);
            break;
        case 3:
            $query .= sprintf(" WHERE dept_code = \"%s\" ", $_SESSION['dept']);
            $query .= sprintf(" AND course_number = \"%s\" ", $_SESSION['course']);
            $query .= sprintf(" AND family_name LIKE \"%s%%\" ", $_SESSION['alphachar']);
            break;
        default:
            $query .= " WHERE family_name ";
            $query .= sprintf("LIKE \"%s%%\"", $_SESSION['alphachar']);
            break;
        }

        echo $query;
        if ($result = mysqli_query($this->con, $query))
        {   
            Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
            Print "<fieldset class=\"input\">";
            Print "<input type=\"hidden\" name=\"author_action\" value=\"2\">";
            Print "<input type=\"submit\" class=\"backl\" name=\"submit\" value=\"" . $alphachar . " [x]\">";
            Print "</fieldset>";
            Print "</form>";
            while ($row = mysqli_fetch_assoc($result))
            {   
                Print "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">";
                Print "<fieldset class=\"input\">";
                Print "<input type=\"hidden\" name=\"alphachar\" value=\"" . $alphachar ."\">";
                Print "<input type=\"hidden\" name=\"family_name\" value=\"" . $row['family_name'] ."\">";
                Print "<input type=\"hidden\" name=\"given_name\" value=\"" . $row['given_name'] . "\">";
                Print "<input type=\"submit\" class=\"link\" name=\"submit\" value=\"" . $row['family_name'] . ", " . $row['given_name'] . "\">";
                Print "<input type=\"hidden\" name=\"author_action\" value=\"4\">\n";
                Print "</fieldset>";
                Print "</form>";
            }
        }
        else
            echo "The selected query failed on execution.";
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
        $query = "SELECT DISTINCT(isbn), title, price, image_url FROM all_book_data "; 
        switch($_SESSION['filter_action'])
        {       
        case 1:
        {
            if ($_SESSION['author_action'] != 1)
                $query .= " WHERE family_name ";
            
            $quest = "";
            $filters = $this->get_filter($_SESSION['author_action']);
            if (isset($_SESSION['id_number']))
            {
                if ($_SESSION['author_action'] == 1)
                    $quest = " WHERE ";
                else
                    $quest = " AND ";
                $quest .= sprintf(" id_number = \"%s\" ", $_SESSION['id_number']);
            }   


            switch($_SESSION['title_action'])
            {
            case 2:
                if ( $_SESSION['author_action'] == 1 && !isset($_SESSION['id_number']))
                    $endline = " WHERE ";
                else
                    $endline = " AND ";

                $endline .= sprintf(" title REGEXP \"^[%s-%s]\" ", $_SESSION['tstart'], $_SESSION['tend']);
                break;
            case 3:
                if ( $_SESSION['author_action'] == 1 && !isset($_SESSION['id_number']))
                    $endline = " WHERE ";
                else
                    $endline = " AND ";

                $endline .= sprintf(" title LIKE \"%s%%\" ", $_SESSION['talphachar']);
                break;
            default:
                break;
            } 

            $query .= $filters . $quest . $endline . " ORDER BY title";
            break;
        }
        case 2:
            $query .= sprintf(" WHERE dept_code = \"%s\" ", $_SESSION['dept']);
            if ($_SESSION['author_action'] != 1)
            {                       
                $query .= " AND family_name ";
            }
            $quest = "";
            $filters = $this->get_filter($_SESSION['author_action']);
            $endline = "";

            switch($_SESSION['title_action'])
            {
            case 2:
                $endline .= sprintf(" AND title REGEXP \"^[%s-%s]\" ", $_SESSION['tstart'], $_SESSION['tend']);
                break;           
            case 3:
                $endline .= sprintf(" AND title LIKE \"%s%%\" ", $_SESSION['talphachar']);
                break;
            default:                                 
                break;
            }

            if (isset($_SESSION['id_number']))
            { 
                $quest = sprintf(" AND id_number = \"%s\" ", $_SESSION['id_number']);
            } 

            $query .= $filters . $quest . $endline . " ORDER BY title";
            break;
            case 3:

                $query .= sprintf(" WHERE dept_code = \"%s\" ", $_SESSION['dept']);
                $query .= sprintf(" AND course_number = \"%s\" ", $_SESSION['course']);

                if ($_SESSION['author_action'] != 1)
                    $query .= " AND family_name ";

                $quest = "";
                $filters = $this->get_filter($_SESSION['author_action']);

                if (isset($_SESSION['id_number']))
                    $quest = sprintf(" AND id_number = \"%s\" ", $_SESSION['id_number']);

                $endline = "";

                switch($_SESSION['title_action'])
                {
                case 2:
                    $endline .= sprintf(" AND title REGEXP \"^[%s-%s]\" ", $_SESSION['tstart'], $_SESSION['tend']);
                    break;
                case 3:
                    $endline .= sprintf(" AND title LIKE \"%s%%\" ", $_SESSION['talphachar']);
                    break;
                default:
                    break;
                }

                $query .= $filters . $quest . $endline . " ORDER BY title";
                break;

            default:
                $query = sprintf("SELECT * FROM Book");
                break;
        }
        echo $query . "\n";
        if ($result = mysqli_query($this->con, $query))
        {    
            if (mysqli_num_rows($result) == 0)
             Print "<h2> No books match your search.</h2>";   
            while ($row = mysqli_fetch_assoc($result))
            {   
                Print "<div class=\"item_box\">";
                Print "<img src=\"" .$row['image_url'] . "\" alt=\"book_image\"/>";
                Print "<h2 class=\"title\">" . htmlspecialchars($row['title']) . "</h2>";
                Print "<p>Author:" . $this->get_authors($row['isbn']);
                Print "</p>";
                Print "<p class=\"isbn\">ISBN: " .$row['isbn'] . "</p>";
                Print "<p class=\"price\">$" .$row['price'] / 100 . "</p>";
                Print "<form action=\"\" method=\"post\">";
                Print "<fieldset class=\"input\">";
                Print "<input type=\"submit\" name=\"add_to_cart\" value=\"Add to Cart\"/>";
                Print "</fieldset>";
                Print "</form>";
                Print "</div>";
            }
        }
        else
        {
            echo "The selected query failed on execution.\n";
            printf("<br/>Errormessage: %s\n", $this->con->error);
        }
    }


    private function get_authors($isbn)
    {
        $authors = "";
        $query = "SELECT family_name, given_name FROM Written ";
        $query .= sprintf("WHERE isbn = \"%s\" ", $isbn);
        if ($result = mysqli_query($this->con, $query))
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                $authors .= " " . $row['family_name'] . " " . $row['given_name'] ." | ";
            }

        }
        else
        {   
            echo "The selected query failed on execution.\n";
            printf("<br/>Errormessage: %s\n", $this->con->error);
        }
        return $authors;
    }

    private function connectToDB($user, $passwd) 
    {
        $conn = mysqli_connect("localhost", $user, $passwd, "bookstore");
        if(!$conn)
        {
            die ("Unable to establish connection to the database.");
        }
        else
        {

            return $conn;
        }
    }
}
?>
