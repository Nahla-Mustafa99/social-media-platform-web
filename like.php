<html>
    <head>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" type="text/css" href="styling/style.css">
    </head>
    <body>
    <style type="text/css">
        * {
            font-family: Arial, Helvetica, Sans-serif;
        }
        body {
            background-color: #fff;
        }

        form {
            position: absolute;
            top: 0;
        }

        </style>
    <?php  
        require 'assets/classes.php';
        $connect =new connection ;
        $con = $connect->conn; 
        session_start();
        if (isset($_SESSION['user'])) {
            $userloggedin = $_SESSION['user']->get_id();
             
            $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE id='$userloggedin'");
            $user = mysqli_fetch_array($user_details_query);
        }
       /* else {
            header("Location: register.php");
        }*/
        if(isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }
        $get_likes = mysqli_query($con, "SELECT likes, added_by FROM posts WHERE id='$post_id'");
        $row = mysqli_fetch_array($get_likes);
        $total_likes = $row['likes']; 
        $user_liked = $row['added_by'];

        $user_details_query=mysqli_query($con, "SELECT * FROM users WHERE id='$user_liked'");
        $row=mysqli_fetch_array($user_details_query);
        $total_user_likes=$row['no_likes'];

        //like button
        if(isset($_POST['like_button'])){

            $total_likes++;
            $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
            $total_user_likes++;
            $user_likes = mysqli_query($con, "UPDATE users SET no_likes='$total_user_likes' WHERE username='$user_liked'");
            $insert_user = mysqli_query($con, "INSERT INTO likes VALUES (NULL, '$userloggedin', '$post_id')");
    
        }
        //unlikebutton
        if(isset($_POST['unlike_button'])){

            $total_likes--;
            $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
            $total_user_likes--;
            $user_likes = mysqli_query($con, "UPDATE users SET no_likes='$total_user_likes' WHERE username='$user_liked'");
            $insert_user = mysqli_query($con, "DELETE FROM likes WHERE username='$userloggedin' AND post_id='$post_id'");
    
        }




            //check for previous likes
            $check_query =mysqli_query($con,"SELECT * FROM likes WHERE username='$userloggedin' And post_id='$post_id'");
            $check_num= mysqli_num_rows($check_query);

        if($check_num > 0)
        {
            echo' <form action ="like.php?post_id='.$post_id.'" method="POST">
            <input type="submit" class="comment_like" name="unlike_button" value="unlike">
            <div class="like_value">
            '.$total_likes.'likes
            </div>
            </form>
            ';
        }
        else
        {
            echo' <form action ="like.php?post_id='.$post_id.'" method="POST">
            <input type="submit" class="comment_like" name="like_button" value="like">
            <div class="like_value">
            '.$total_likes.'likes
            </div>
            </form>
            ';
        }





        ?>


    </body>


</html>