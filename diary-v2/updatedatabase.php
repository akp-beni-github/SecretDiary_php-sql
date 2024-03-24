
<?php 
    session_start();

    if(array_key_exists("content", $_POST)) {//look into array of POST if content array key not null then ...
        
        $link = mysqli_connect (  "sql209.infinityfree.com", 
        "if0_36145394", 
        "tOXZcktiSJ1BiEn", 
        "if0_36145394_note_users");


        if(mysqli_connect_error()) {
            die("Data Connection Error");
        }


        //update diary where id =$_session[id] (id from login part)
        $query = "UPDATE users SET diary = '" .
            mysqli_real_escape_string($link, $_POST['content']) . "' WHERE id = " . mysqli_real_escape_string($link, $_SESSION['id']) . " LIMIT 1";

        mysqli_query($link, $query);

    }
    
?>