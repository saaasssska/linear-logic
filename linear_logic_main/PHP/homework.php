<!DOCTYPE>
<html>
    <body>
        <?php
            echo "<h2>Hello. My name is Sergey Goryunov. My group is 231-328. I'm really intrested by web porgramming? but it's reaaly fast for 1st lesson((\n<h2>";
            
            echo "First Task";
            echo "<br>"; 
            $thisdate = "2012-09-12";
            echo $thisdate;
            echo "<br>"; 

            $revDate = date("d-m-Y", strtotime($thisdate));
            echo $revDate;

            echo "<br><br><br>"; 

            echo "Second Task";
            echo "<br>"; 

            $x = "My PHP Homework";
            echo $x;
            echo "<br>"; 

            echo strrev($x);
            

            ?>
            </body>
</html>