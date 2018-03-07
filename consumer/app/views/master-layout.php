<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>NeoAssist Tickets API consumer</title>

        <!-- Bootstrap core CSS & Font Awesome -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/custom.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="container">
            <?php echo $content; ?>
        </div> <!-- /container -->
        
        <!-- Global var -->
        <script>
            var BaseUrl = "<?php echo $base_url; ?>";
        </script>
        
        <!-- Script Libraries -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="vendor/moment/moment-with-locales.min.js"></script>
        <script src="vendor/jscookie/js.cookie.js"></script>
        <script src="js/URI.js"></script>
        
        <!-- Application script -->
        <script src="js/app.js"></script>
    </body>
</html>
