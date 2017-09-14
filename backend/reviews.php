<?php
session_start();
include 'functions.php';
include 'db_connect.php';
require_once('nocsrf.php');

try
{
    // Check CSRF token
    NoCSRF::check( 'csrf', $_POST, true, 60*10, true );

    // Get Post Data
    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $body = $_POST['body'];
    $teacher = $_POST['teacher'];

    // Secure against XSS
    $name = secure_input($name);
    $rating = secure_input($rating);
    $body = secure_input($body);
    $teacher = secure_input($teacher);

    // Secure against SQL injection
    $name = mysqli_real_escape_string($dbconfig,$name);
    $rating = mysqli_real_escape_string($dbconfig,$rating);
    $body = mysqli_real_escape_string($dbconfig,$body);
    $teacher = mysqli_real_escape_string($dbconfig,$teacher);

    // Check that they're not empty
    if(empty($name) || empty($rating) || empty($body) || empty($teacher)) {
        echo "Please fill all required inputs";
        die();
    }

    $query = mysqli_query($dbconfig, "INSERT INTO reviews (teacher_id, author, rating, body) VALUES ('$teacher', '$name', '$rating', '$body')");
    if($query) {
        echo "Review added succesfully";
    } else {
        echo "Error adding review";
    }

} catch ( Exception $e ) {
    // CSRF attack detected
    echo "An error was detected was your csrf token. This can usually be solved by refreshing the page.";
}