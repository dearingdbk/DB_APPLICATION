<?php

$insert = " LOCK TABLES Bookorder WRITE, Stocks WRITE, Contains WRITE; ";
$insert .= "INSERT INTO Bookorder (order_date, id_number) ";
$insert .= sprintf(" VALUES(\"%s\", \"%s\"); ", date("Y-m-d"), $_SESSION['login_id']);
$insert .= "SELECT LAST_INSERT_ID();";

if (mysqli_multi_query($this->con, $insert))
{

    mysqli_next_result($this->con); // skip lock.
    mysqli_next_result($this->con); // skip insert.

    if ($result = mysqli_store_result($this->con))
    {
        $row = mysqli_fetch_row($result);
        mysqli_free_result($result);
        $order_id = $row[0];

        while (mysqli_next_result($this->con));

        $rtn_val = true;
        foreach($this->items as $isbn => $dingo)
        {
            $insert = "INSERT INTO Contains VALUES ";
            $insert .= sprintf(" ( \"%s\", \"%s\", 0, \"%s\"); ", $isbn, $order_id, $dingo['qty']);
            $insert .= sprintf("SELECT quantity FROM Stocks WHERE isbn = \"%s\" ", $isbn);
            $insert .= sprintf("AND store_id = \"%s\"; ", $_SESSION['store']);
            $insert .= "UPDATE Stocks SET quantity = ";
            $insert .= sprintf("quantity - \"%s\" WHERE isbn = \"%s\" ", $dingo['qty'], $isbn);
            $insert .= sprintf("AND store_id = \"%s\" ; ", $_SESSION['store']);

            if (mysqli_multi_query($this->con, $insert))
            {
                do
                {
                    if ($result = mysqli_store_result($this->con))
                    {
                        $row = mysqli_fetch_row($result);
                        mysqli_free_result($result);
                        if ($row[0] - $dingo['qty'] <  0)
                        {
                            $rtn_val = false;
                            printf("<h2> Not enough items in stock to fill order of %s.", $isbn);
                            printf("<br/> requested: %s | stocked: %s </h2>", $dingo['qty'], $row[0]);
                        }
                    }

                } while (mysqli_next_result($this->con));
            }
            else
            {
                printf("<br/>Errormessage: %s\n", $this->con->error);
                $rtn_val = false;
            }
        } // end of foreach.
    }
    else
    {
        printf("<br/>Errormessage: %s\n", $this->con->error);
        $rtn_val = false;
    }
}
else
{
    printf("<br/>Errormessage: %s\n", $this->con->error);
    $rtn_val = false;
}   

if ($rtn_val)
{
    
    mysqli_query($this->con, "UNLOCK TABLES");
    mysqli_commit($this->con);
    $_SESSION['confirmation'] = $order_id;
    $this->empty_cart();
    unset($_SESSION['login_id']);
}
else
{
    mysqli_rollback($this->con);
    mysqli_query($this->con, "ROLLBACK");
    mysqli_query($this->con, "UNLOCK TABLES");
}

?>
