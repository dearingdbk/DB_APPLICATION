<?php
Print "<h2>Courses</h2>\n"; 
$query = "SELECT * FROM Requires WHERE ";
$query .= sprintf("dept_code = \"%s\" ", $_SESSION['dept']);
$query .= "ORDER BY course_number";
if ($result = mysqli_query($this->con, $query))
{
    $this->createForm("",
        array(array('type' => 'hidden', 'name' => 'filter_action', 'value' => 1),
        array('type' => 'submit', 'name' => 'submit', 
        'value' => $_SESSION['dept'] . " [x]", 'class' => 'backl')));
    
    while ($row = mysqli_fetch_assoc($result)) 
    {
        $button = $row['dept_code'] . " [";
        $button .= $row['course_number'] . "] [";
        $button .= $row['section_code'] . $row['term_number'] . "]";

        $this->createForm("",
            array(array('type' => 'hidden', 'name' => 'dept', 'value' => $row['dept_code']),
            array('type' => 'hidden', 'name' => 'course', 'value' => $row['course_number']),
            array('type' => 'hidden', 'name' => 'filter_action', 'value' => 3),
            array('type' => 'submit', 'name' => 'submit', 'value' => $button, 'class' => 'link')));
    }   
}

?>
