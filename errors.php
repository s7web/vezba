<html>
<head>
    <title><?php echo SITE_NAME; ?> - Error</title>
    <style>
        body{
            background: black;
            color:red;
        }
        .wrapper{
            width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h1>Error has occurred</h1>
    <div class="error">
        <?php if (DEBUG_MODE) : ?>

            <h3><?php echo $error; ?></h3>

        <?php else : ?>
            <h3>
                Oops something went wrong, you search for something what does not exists!
            </h3>
            <h4><a href="<?php echo SITE_URL; ?>">Please go to home page.</a></h4>
        <?php endif; ?>
    </div>
    <div class="trace">
        <?php if( DEBUG_MODE ) : ?>
        <pre>
        <p><?php print_r( $trace ); ?></p>
        </pre>
        <?php endif; ?>
    </div>
</div>
</body>
</html>