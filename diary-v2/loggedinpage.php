<?php
    session_start();
    $diaryContent = "";  

    if(array_key_exists("id", $_COOKIE)) {
        $_SESSION['id'] = $_COOKIE['id'];
    }

    if(array_key_exists("id", $_SESSION)) {

        $link = mysqli_connect (  "sql209.infinityfree.com", 
        "if0_36145394", 
        "tOXZcktiSJ1BiEn", 
        "if0_36145394_note_users");

        $query = "SELECT * FROM users WHERE id = " . mysqli_real_escape_string($link,$_SESSION['id']) . " LIMIT 1 ";
                $result = mysqli_query($link, $query); //retrieve 
                $row = mysqli_fetch_array($result); // store in array
                
        $email= $row['email'];
        $diaryContent = $row['diary'];

    }
    else {
        header("Location: index.php");
    }
?>





<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" contnt="ie=edge">
        <title>Secret Diary</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
              rel="stylesheet" 
              integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
              crossorigin="anonymous">

        
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    </head>







<body>
<div class="container">

    <nav class="navbar navbar-light bg-faded navbar-fixed-top">
        <a class="navbar-brand" href="#">Secret Diary Project</a>



        <div class="pull-xs-right">
            <?php echo "<p>You logged in as <span style=\"color: green;\">" . $email  ;?>
            <a href="index.php?logout=1"> <!--http://example.com/path/to/resource?key1=value1&key2=value2 then $_GET to COOKIE AND SESSION later on--> 
                <button class = "btn btn-success-outline">Logout</button>
            </a>
        </div>
    </nav>  

    <br>

    
    <div class="container-fluid">
        <!--method='post' using java--> 
        <textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>
    </div>


<footer class="pt-3 mt-4 text-muted border-top">
        By Akapoomwit Puangsricharern
</footer>    
</div>    
</body>








<!--footer-->
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
                integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
                crossorigin="anonymous"></script>






        <script type="text/javascript">
                $("#diary").bind('input propertychange', function() { //function's triggered everytime input property change (value change)
                $.ajax({
                    method: "POST",             //send diary.val to $_post['content']
                    url: "updatedatabase.php",  //update query to database
                    data: {content: $("#diary").val()}  //the diary.val
                });
            });
        </script>
       

</html>




