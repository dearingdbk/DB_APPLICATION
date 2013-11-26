<?php        

if (isset($_SESSION['id_number']))
{
    $this->createForm("",
        array(array('type' => 'hidden', 'name' => 'drop_id',
         'value' => 3),
        array('type' => 'submit', 'name' => 'submit', 
              'value' => $_SESSION['id_number'] . " [x]",
               'class' => 'backl')));
}
else
{
    Print "<h2>Student ID</h2>\n";

    $this->createForm("",
        array(array('type' => 'text', 'name' => 'id_number',
         'place' => 'ID Number'),
        array('type' => 'submit', 'name' => 'submit',
         'value' => 'submit')));
}
?>
