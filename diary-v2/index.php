<?php

    session_start();
    $error = "";
    //$user ="";
    //$domain="";

//cookies&session//
    if(array_key_exists("logout", $_GET)) { // (1)get logout=1 from url
        session_unset();
        setcookie("id", "", time() - 60 * 60);
        $_COOKIE["id"] = "";//clear id to logout
    }
    else if(array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)) { //(or 2) id in session or cookie > login with that id
        header("Location: loggedinpage.php");
    }
//////////////////



    if(array_key_exists("submit", $_POST)) 
    {
        
        $link = mysqli_connect (  "sql209.infinityfree.com", 
        "if0_36145394", 
        "tOXZcktiSJ1BiEn", 
        "if0_36145394_note_users");


        if(mysqli_connect_error()) {
            die("Data Connection Error");
        }






        if(!$_POST['email']) {
            $error .= "An email address is required.<br>";
        }

        if(!$_POST['password']) {
            $error .= "A password is required.<br>";
        }

          
        if($_POST["email"] && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) //if email valid (filter===true) 
        //using && if on of these condition false will trigger this function
        {
            $error .="invalid email address <br>"; //without@

            
        }

        if($_POST["email"] && strpos($_POST["email"], '@gmail.com') === false) {
            $error .= "Only Gmail addresses are allowed.<br>";//@gmail
        }

    

        
        if($error != "") {
            $error = "<p>There were error(s) in your form!</p>" . $error;
        }


        else {//no error
            $emailAddress = mysqli_real_escape_string($link, $_POST['email']);
            $password = mysqli_real_escape_string($link, $_POST['password']); 
            $password = password_hash($password, PASSWORD_DEFAULT);

            if($_POST['signUp'] == '1')//signup
             {
                $query  = "SELECT id FROM users WHERE email = '" . $emailAddress . "' LIMIT 1"; //select to check

                $result = mysqli_query($link, $query);//declare one time but if the query changed it will also change
    
                if(mysqli_num_rows($result) > 0) {
                    $error = "That email address is taken.";
                }
                else {//post to server
                    $query = "INSERT INTO users (email, password) VALUES ('" . $emailAddress . "', '" . $password . "')"; 
    
                    if(!mysqli_query($link, $query)) {
                        $error .= "<p>Could not sign you up - Please try again later.</p>";
                        $error .= "<p>" . mysqli_error($link) . "</p>";
                    }
                    else {
                        $id = mysqli_insert_id($link);
    
                        $_SESSION['id'] = $id;//login after page refresh from submit
    
                        if(isset($_POST['stayLoggedIn'])) { //isset = not null
                            setcookie("id", $id, time() + 60 * 60 * 24 * 365);//a year
                        }
    
                        header("Location: loggedinpage.php");
    
                    }
                }
            }
            elseif($_POST['signUp'] == '0')//login 
            {
                $query = "SELECT * FROM users WHERE email = '" . $emailAddress . "'";
                $result = mysqli_query($link, $query); //retrieve whole row where email = post mail, but result is just a boolean without fetching
                $row = mysqli_fetch_array($result); // store in array
                $password = mysqli_real_escape_string($link, $_POST['password']); //store current pass to compare

                if(isset($row) AND array_key_exists("password", $row)) {//
                    $passwordMatches = password_verify($password, $row['password']); 

                    if($passwordMatches) {
                        $_SESSION['id'] = $row['id'];
                        if(isset($_POST['stayLoggedIn'])) {
                            setcookie("id", $row['id'], time() + 60 * 60 * 24 * 365);
                        }

                        header("Location: loggedinpage.php");
                    }
                    else {
                        $error = "That email/password combination could not be found.";
                    }
                }
                else {
                    $error = "That email/password combination could not be found.";
                }
            }
        }



    }

?>

<!--header-->
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


<!--body-->
<body>
<main>    
                        <div class="container" id="homePageContainer">

                        <header class="pb-3 mb-4 border-bottom">
                            <h1></h1>
                            <span class="display-6">Secret Diary Project</span>
                        </header>


<span id="error">
<?php 
if ($error!=""){
                        
    echo '<div class="alert alert-danger" role="alert"> ' . $error . '</div>';
}
?>
</span>
                <div class="row">
                    <!-- log in form -->
                    <div class="col-md-8"> <!--mx-auto to make it middle-->

                        <form method="post" id="loginform" >

                        <h6 class="fs-3">Log in using your username and password</h6>

                        <!--fieldset class from bootstrap-->
                            <fieldset class="form-group">
                                <input type="email" name="email" placeholder="Your email" class="form-control">
                            </fieldset>

                            <fieldset class="form-group">
                                <input type="password" name="password" placeholder="Password" class="form-control">
                            </fieldset>
                            
                            <fieldset class="checkbox">
                                Stay Logged In:    <input type="checkbox" name="stayLoggedIn" value="1">
                            </fieldset>

                            <fieldset class="form-group">
                                <input type="hidden" name="signUp" value="0">
                                <input type="submit" name="submit" value="Log In" class="btn btn-success">
                            </fieldset>

                        </form>
                        

                    <a class="toggleForms" id="signuptoggle" >Sign Up!</a>
                    <!--<p><a class="toggleForms"> Log In </a></p>-->


                    </div>    

                            
                    <div class="col-md-8"> <!--making sure it not too large out of the screen-->
                            <!-- sign up form normally hidden-->
                            <form method="post" id="signupform" style="display: none;" title="Sign Up form">

                            <h6 class="fs-3">Interested?  Sign up now!</h6>

                            <fieldset class="form-group">
                                <input type="email" name="email" placeholder="Your email" class="form-control">
                            </fieldset>

                            <fieldset class="form-group">
                                <input type="password" name="password" placeholder="Password" class="form-control" >
                            </fieldset>
                            
                            <fieldset class="checkbox">
                                Stay Logged In:    <input type="checkbox" name="stayLoggedIn" value="1" >
                            </fieldset>

                            <fieldset class="form-group">
                                <input type="hidden" name="signUp" value="1">

                                <input type="submit" name="submit" value="Sign Up!" class="btn btn-success">
                            </fieldset>    

                            </form>   
                    </div>
                </div>



<footer class="pt-3 mt-4 text-muted border-top">
        By Akapoomwit Puangsricharern <a href="https://github.com/akp-beni-github" target="_blank">viewing my source code</a>
</footer>


</div>
</main>
</body>






        <!--footer-->
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
                integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
                crossorigin="anonymous"></script>


        
       
       
        
        

        <script type="text/javascript">

            //popup jquery
            $(document).ready(function() {

                $("#signuptoggle").click(function() {
                    $("#signupform").dialog({
                    //width: 705, // Initial width of the dialog
                    //height: 311 // Initial height of the dialog
                    });
                });
            });


            /*$(".toggleForms").click(function() {
                //toggle the forms
                $("#signupform").toggle();
                $("#loginform").toggle();
            });*/


        </script>


</html>