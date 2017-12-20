<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?=isset($title) ? $this->e($title)." | " : ""?>YACRS</title>
        <meta name="description" content="<?=$this->e($description)?>">

        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="#003865">
        <link rel="apple-touch-icon" sizes="128x128" href="<?=$config["baseUrl"]?>img/uofg/icon_hi.png">
        <link rel="apple-touch-icon" sizes="64x64" href="<?=$config["baseUrl"]?>img/uofg/icon_small.png">

        <title>YACRS</title>




        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet">



        <link rel="stylesheet" href="<?=$config["baseUrl"]?>css/bootstrap-4.0.0-beta.2.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?=$config["baseUrl"]?>css/style.css" rel="stylesheet">
        <link href="<?=$config["baseUrl"]?>css/bootstrap-extra.css" rel="stylesheet">
        <?=$this->section("head")?>
    </head>
    <body>
        <?php $this->insert("partials/navigation", ["config" => $config, "logo" => $logo, "user" => $user]) ?>
        <main role="main">
            <?php $this->insert("partials/breadcrumb", ["breadcrumbs" => $breadcrumbs]) ?>
            <?=$this->section("preContent")?>
            <div class="container">
                <?php $this->insert("partials/alert", ["alert" => $alert]) ?>
                <?=$this->section("content")?>
            </div>
            <?=$this->section("postContent")?>
        </main>
        <footer class="footer">
            <?php $this->insert(isset($footer) ? $footer : "partials/footer", ["config" => $config]) ?>
        </footer>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?=$config["baseUrl"]?>js/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
        <script src="<?=$config["baseUrl"]?>js/popper.min.js"></script>
        <script src="<?=$config["baseUrl"]?>js/bootstrap-4.0.0-beta.2.min.js"></script>

        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
            var baseUrl = "<?=$config["baseUrl"]?>";
        </script>

        <script src="<?=$config["baseUrl"]?>js/bootstrap-extra.js" crossorigin="anonymous"></script>

        <?=$this->section("end")?>
    </body>
</html>