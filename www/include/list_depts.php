<?php
Print "<h2>Departments</h2>\n";
$query = "SELECT DISTINCT(dept_code) FROM Requires ORDER BY dept_code";
if ($result = mysqli_query($this->con, $query))
{
    while ($row = mysqli_fetch_assoc($result))
    {
        $this->createForm("",
            array(array('type' => 'hidden', 'name' => 'dept',
             'value' => $row['dept_code']),
            array('type' => 'hidden', 'name' => 'course',
             'value' => $row['course_number']),
            array('type' => 'hidden', 'name' => 'filter_action',
             'value' => 2),
            array('type' => 'submit', 'name' => 'submit', 
            'value' => $row['dept_code'], 'class' => 'link')));
    }
}
?>
