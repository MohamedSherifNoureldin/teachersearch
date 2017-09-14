$(document).ready(function(e) {
    $("#newreview").on('submit', (function(e) {
        e.preventDefault();
        var name = $("#reviewname").val();
        var rating = $("#rating").val()
        var body = $("#body").val();
        var teacher = $("#teacher").val();
        var csrf = $("#csrf").val();
        if (name == '' || rating == '' || body == '') {
            alert("Please Fill Required Fields");
        } else {
            $.ajax({
                url: "../backend/reviews.php",
                type: "POST",
                data: {
                    name: name,
                    rating: rating,
                    body: body,
                    teacher: teacher,
                    csrf: csrf
                },
                cache: false,
                success: function(data) {
                    alert(data);
                },
                error: function() {
                    alert("Sorry an error has occured");
                },
            });
        }
    }
    ));
});