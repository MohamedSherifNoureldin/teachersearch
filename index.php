<?php
include 'backend/db_connect.php';
include 'backend/functions.php';

// Variable defining to prevent notice
$query = "";

if(!empty($_GET['query'])){
    // Get Search Query 
    $query = $_GET['query'];
    $query = secure_input($query);
    $query = mysqli_real_escape_string($dbconfig,$query);
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
        <link rel="stylesheet" href="css/theme.min.css">

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

            <!-- Search Form -->
            <form class="form-inline" method="GET">
                <input type="text" class="form-control mr-2" id="name" placeholder="Teacher's name or Subject" autocomplete="off" autocorrect="off" spellcheck="false" name="query" value="<?php echo $query;?>">
                <button type="submit" class="btn">Search</button>
            </form>
            
            <!-- Results -->
            <?php
                // Check that the query is not empty and is not less than 2 character
                if (!empty($query) && strlen($query)>=2 ) {
                    // Select from the databse by name or subject
                    $search_select = "SELECT * FROM teachers WHERE name LIKE '%$query%' OR subject LIKE '%$query%' ORDER BY name";
                    $searchresult = mysqli_query($dbconfig,$search_select);
                    // Check that the results are not empty
                    if(mysqli_num_rows($searchresult) == 0){
                        echo "Sorry ! Nothing was found";
                    } else {
                        // Show each and every result in this style
                        while($teacher = mysqli_fetch_assoc($searchresult)) {
            ?>
                            <div class="media text-left mt-3">
                                <!-- Teacher's Image -->
                                <img class="d-flex align-self-center mr-3 img-thumbnail" src="https://via.placeholder.com/100x100/ffd935/ffffff?text=<?php echo $teacher['name'][0]; ?>" alt="Generic placeholder image" width="110px" height="110px">
                                <div class="media-body">
                                    <!-- Teacher's Name -->
                                    <h4><?php echo $teacher['name'] ?></h4>
                                    <!-- Teacher's Center -->
                                    <p class="float-left"><i class="fa fa-home" aria-hidden="true"></i> <?php echo $teacher['center'] ?></p>
                                    <!-- Teacher's Location -->
                                    <p class="float-right pr-3"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $teacher['location'] ?></p>
                                    <!-- Teacher's Subject -->
                                    <p style="clear: both"><i class="fa fa-pencil" aria-hidden="true"></i> <?php echo $teacher['subject'] ?></p>
                                    <!-- Teacher's Phone Number -->
                                    <p><i class="fa fa-phone" aria-hidden="true"></i> <?php echo $teacher['phone'] ?></p>
                                </div>
                            </div>
            <?php 
                    }
                }
            } else {
                // Not enough letters were entered
                echo "Sorry ! Nothing was found. Try entering more letters.";
            }
            ?>

        </div>
        
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    </body>

</html>