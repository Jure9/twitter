<footer class="footer">
    <div class="container">

    <p>&copy; Twiter 2016</p>
    </div>
</footer>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="alert alert-danger" id="errorMsg"></div>
                    <input type="hidden" id="loginActive" name="loginActive" value="1">

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" placeholder="Email address">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a id="toggleLogin" href="#">Sign up</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="loginSignupButton" class="btn btn-primary">Login</button>
            </div>
        </div>
    </div>
</div>


<script>


    $("#toggleLogin").click(function () {

        if ($("#loginActive").val() === "1") {
            $("#loginActive").val("0");
            $("#myModalLabel").html("Sign Up");
            $("#toggleLogin").html("Login");
            $("#loginSignupButton").html("Sign Up");
        }
        else {
            $("#loginActive").val("1");
            $("#myModalLabel").html("Login");
            $("#toggleLogin").html("Sign Up");
            $("#loginSignupButton").html("Login");
        }


    })

    $("#loginSignupButton").click(function() {

        $.ajax({
            type: "POST",
            url: "actions.php?action=loginSignup",
            data: "email=" + $("#email").val() +
            "&password=" + $("#password").val() +
            "&loginActive=" + $("#loginActive").val(),
            success: function (result) {

                if(result == "1") {
                    window.location.assign("index.php");
                }
                else
                {
                    $("#errorMsg").html(result).show();
                }
            }
        });


    });

    $(".follow").click(function() {

        var id=$(this).attr("data-userId");

        $.ajax({
            type: "POST",
            url: "actions.php?action=toggleFollow",
            data: "userId=" + id,
            success: function (result) {

                if(result == "1")
                {
                    $("a[data-userId='" + id + "']").html("Follow");
                }
                else
                {
                    $("a[data-userId='" + id + "']").html("UnFollow");
                }
            }
        });
    });
    $("#postTweetButton").click(function(){

        $.ajax({
            type:"POST",
            url:"actions.php?action=postTweet",
            data:"tweet=" + $("#postTweetArea").val(),
            success:function(result)
            {
                if(result == "1")
                {
                    $("#postSuccess").html("Post was sent successfully").show();
                    $("#postError").hide();
                }
                else if(result != "")
                {
                    $("#postError").html(result).show();
                    $("#postSuccess").hide();
                }
            }
        })

    })
</script>
</body>
</html>