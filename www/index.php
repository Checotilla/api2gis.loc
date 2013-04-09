<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>api2gis</title>

        <style type="text/css">
            body{
                background-color: buttonface;
            }
            td{
                padding:30px;
            }
            
            .header{
                background-color: lightgreen;
            }
            .main{
                background-color: #ffffff;
                height: 80%;
                vertical-align: top;
            }
            .footer{
                background-color: lightgreen;
            }

        </style>

    </head>
    <body>

        <table border="0" width ="70%" align="center" height="800px">
<!-- HEADER -->
            <tr class="header">
                <td>
                    <h1>api2gis - вытаскиваем справочники</h1>

                </td>
            </tr>

<!-- MAIN -->
            <tr class="main">
                <td>
<?php

    include_once("api2gis/api2gis.php");
    
    $api2gis = new Api2gis('ruhlhy8961');
    $result = $api2gis->LoadProjectsList();
    echo $result;

?>
				
				
				


                </td>
            </tr>
<!-- FOOTER -->
            <tr class="footer">
                <td>
                    <a href="http://api.2gis.ru">api.2gis.ru</a>
                    |
                </td>
            </tr>
        </table>

    </h3>
</body>
</html>
