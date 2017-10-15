
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo $title?></title>

        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/styles.css?r=2" rel="stylesheet">
        <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
        <script src="/assets/js/jquery-3.2.1.min.js"></script>
    </head>

    <body>

        <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
            <a class="navbar-brand" href="/">Orwell scan</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Block list <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/databases">Databases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/nodes">Peer info</a>
                    </li>
                </ul>
                <form action='/' method='GET' class="form-inline mt-2 mt-md-0">
                    <input name='q' class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>

        <div class="container content-container">


            <?php echo $content ?>


        </div>

        <div class="black footer">

            <div class="container text-center">

                <div> 
                    <?php echo Config::$title?> is a Block Explorer for Orwell, platform for building decentralized applications.
                </div>
                <br />

                © 2017 Powered by <a target='_blank' href='http://twitter.com/orwellcat'>Nanocat</a> <a target="_blank" rel='nofollow' href='https://github.com/gettocat/orwellscan'>Source code</a>

            </div>

        </div>


        <script src="/assets/js/popper.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
    </body>
</html>
