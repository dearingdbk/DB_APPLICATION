<?php
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
if ($result = mysqli_query($this->con, $query))
{
    if (mysqli_num_rows($result) == 0)
        Print "<h2> No books match your search.</h2>";
    while ($row = mysqli_fetch_assoc($result))
    {
        Print "<div class=\"item_box\">\n";
        Print "<img src=\"" .$row['image_url'] . "\" alt=\"book_image\"/>\n";
        Print "<h2 class=\"title\">" . htmlspecialchars($row['title']) . "</h2>\n";
        Print "<p>Author:" . $this->get_authors($row['isbn']);
        Print "</p>\n";
        Print "<p class=\"isbn\">ISBN: " .$row['isbn'] . "</p>\n";
        Print "<p class=\"price\">$" .$row['price'] / 100 . "</p>\n";
        $this->createForm("",
            array(array('type' => 'hidden', 'name' => 'cart_action', 'value' => 1),
                  array('type' => 'hidden', 'name' => 'isbn', 'value' => htmlspecialchars($row['isbn'])),
                  array('type' => 'hidden', 'name' => 'price', 'value' => $row['price']),
                  array('type' => 'hidden', 'name' => 'title', 'value' => $row['title']),
                  array('type' => 'hidden', 'name' => 'qty', 'value' => 1),
                  array('type' => 'submit', 'name' => 'add_to_cart', 'value' => 'Add to Cart')));

        Print "</div>\n";
    }
}
else
{
    echo "The selected query failed on execution.\n";
    printf("<br/>Errormessage: %s\n", $this->con->error);
}

?>
