
<div class="container mainContainer">

    <div class="row">
        <div class="col-md-8">

            <?php if(isset($_GET['user_id'] )){ ?>

            <?php displayTweets($_GET['user_id']); ?>

            <?php } else{ ?>

                <h2>Display users:</h2>

            <?php displayUsers() ?>

            <?php }?>

        </div>
        <div class="col-md-4">

            <?php displaySearchBox(); ?>

            <hr>

            <?php displayTweetBox(); ?>

        </div>
    </div>

</div>
