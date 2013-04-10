<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once ('require.php');
        // put your code here
        
            $DB->query('TRUNCATE TABLE  `rubric`');
            $DB->query('TRUNCATE TABLE  `rubricator`');
        ?>
    </body>
</html>
