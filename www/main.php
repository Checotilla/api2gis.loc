<?php
    require_once 'require.php';

    $api2gis = new Api2gis('ruhlhy8961');
    $result = $api2gis->LoadRubricatorToDB(true);
    echo "<div style='background:#9ff;'><xmp>$result</xmp></div>";
    //echo '<iframe src="'."data/answers/1365505977.xml".'" width="100%">Не поддерживаются плавающие фреймы</iframe>';
    
?>
