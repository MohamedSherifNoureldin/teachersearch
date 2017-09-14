<?php
session_start();

include 'backend/db_connect.php';
include 'backend/functions.php';
require_once('backend/nocsrf.php');

// Generate CSRF token
$csrftoken = NoCSRF::generate( 'csrf' );

// Check if page was called directly without any get parameters
if(empty($_GET)) {header("Location: http://localhost/ig"); die();}

// Get the teacher from the url
$teacher = $_GET['teacher'];
$teacher = secure_input($teacher); // Secure against xss
$teacher = mysqli_real_escape_string($dbconfig,$teacher); // Secure against sql inejction

// Select the teacher with this username
$teacher_select = "SELECT * FROM teachers WHERE username = '$teacher'";
$teacheresult = mysqli_query($dbconfig,$teacher_select);

$teacher = mysqli_fetch_assoc($teacheresult);
$teacher_id = $teacher['id'];

// Check that the username is valid
if(mysqli_num_rows($teacheresult) == 0){
    // Username doesn't exist transfer user to search page
    header("Location: http://localhost/ig/?query=".$teacher);
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

        <!-- Theme's CSS -->
        <link rel="stylesheet" href="//localhost/ig/css/theme.min.css">

        <!-- Font Awesome --> 
        <script src="https://use.fontawesome.com/541c5099b7.js"></script>
    </head>

    <body>
        <!-- Navigation Bar -->
        <nav class="navbar navbar-light">
            <a class="navbar-brand" href="#">
                <img src="https://ig.academy/wp-content/uploads/2017/08/Academy-Flat-icon-1.png" height="50" alt="Ig Academy">
            </a>
        </nav>

        <div class="card holder container text-center">

            <!-- Teacher's Name and Average Rating -->
            <div class="text-left">
                <h2><?php echo $teacher['name'] ?></h2>
                <?php
                // Select average rating of teacher
                $ratingresult = mysqli_query($dbconfig,"SELECT AVG(rating) AS avgrating FROM reviews WHERE teacher_id = '$teacher_id'");
                $avgrating = mysqli_fetch_assoc($ratingresult);
                $avgrating = $avgrating['avgrating'];

                // Add full stars
                for ($i=$avgrating;$i <= 5 && $i >= 1; $i--) { 
                ?>
                    <i class="fa fa-star fa-2" aria-hidden="true"></i>
                <?php 
                } 
                
                // Get remaing stars
                $empty_stars = 5 - $avgrating;
                for ($i=$empty_stars;$i <= 5 && $i >= 0; $i--) {
                ?>
                    <i class="fa fa-star-o fa-2" aria-hidden="true"></i>
                <?php
                }
                ?>
            </div>

            <hr>

            <!-- List of Reviews -->
            <ul class="list-unstyled text-left mr-3 ml-3 mb-0 mt-2">
                <?php
                // Select all reviews using the teacher's id
                $reviews_select = "SELECT * FROM reviews WHERE teacher_id = '$teacher_id' ORDER BY rating DESC";
                $reviewsresult = mysqli_query($dbconfig,$reviews_select);

                // Check that the results are not empty
                if(mysqli_num_rows($reviewsresult) == 0){
                    echo "Sorry ! Nothing was found";
                } else {
                    // Show each and every review in this style
                    while($review = mysqli_fetch_assoc($reviewsresult)) {
                ?>
                    <li class="media mb-3">
                        <div class="media-body">
                            <h4 class="mt-0 mb-1"><?php echo $review['author']; ?></h4>
                            <?php echo $review['body']; ?>
                        </div>
                        <?php
                        for ($i=$review['rating'];$i <= 5 && $i >= 1; $i--) { 
                        ?>
                            <i class="fa fa-star fa-2 d-flex align-self-center" aria-hidden="true"></i>
                        <?php 
                        } 
                        $empty_stars = 5 - $review['rating'];
                        for ($i=$empty_stars;$i <= 5 && $i >= 1; $i--) {
                        ?>
                            <i class="fa fa-star-o fa-2 d-flex align-self-center" aria-hidden="true"></i>
                        <?php
                        }
                        ?>
                    </li>
                <?php
                    }
                }
                ?>
            </ul>

            <hr>

            <!-- Add new review form -->
            <form class="text-left mr-3 ml-3 mt-2" id="newreview" method="POST" action="/posts">
                <h3>Post A Review</h3>
                <!-- Name -->
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <input type="text" class="form-control col-sm-10" id="reviewname" name="name" required="">
                </div>
                <!-- Rating -->
                <div class="form-group row">
                    <label for="rating" class="col-sm-2 col-form-label">Rating</label>
                    <select class="form-control col-sm-10" id="rating">
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                </div>
                <!-- Body -->
                <div class="form-group row">
                    <label for="body" class="col-sm-2 col-form-label">Body</label>
                    <textarea class="form-control col-sm-10" id="body" name="body" rows="5" required=""></textarea>
                </div>
                <input type="hidden" id="csrf" value="<?php echo $csrftoken ?>">
                <input type="hidden" id="teacher" value="<?php echo $teacher_id ?>">
                <button type="submit" class="btn float-right mr-2" style="padding: .5rem .75rem;">Publish</button>
            </form>
        </div>
        
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="//localhost/ig/js/reviews.js"></script>
    </body>

</html>
