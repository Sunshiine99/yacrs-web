<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <title>Live View</title>

    <script>
        function exitLiveView(sessionIdentifier) {
            const electron = require('electron');
            let {ipcRenderer} = electron;
            ipcRenderer.send("exitLiveView");
        }
    </script>
    <style>
        body {
            background-color: #003865;
            -webkit-app-region: drag;

            font-size: 28px;
            color: white;

            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;

        }
        img{
            height: 50px;
        }

        button, a {
            color: white;
            font-size: 25px;
            -webkit-app-region: no-drag;
        }

    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-1">
            <img src="<?=$this->e($config["baseUrl"])?>img/uofg/logo-gu-icon.png"></img>
        </div>
        <div class="col-md-1">
            <b>#/#</b>
        </div>
        <div class="col-md-2">
            <b>Activate</b>
        </div>
        <div class="col-md-1">
            <i class="fa fa-angle-double-right"></i>
        </div>
        <div class="col-md-3">
            <b>This is the question</b>
        </div>
        <div class="col-md-1">
            <a href="#" onclick="exitLiveView()">
                <i class="fa fa-power-off"></i>
            </a>
        </div>
<!--        <div class="col-md-1">-->
<!--            <i class="fa fa-pie-chart" aria-hidden="true"></i>-->
<!--        </div>-->
<!--        <div class="col-md-1">-->
<!--            <i class="fa fa-bar-chart" aria-hidden="true"></i>-->
<!--        </div>-->
    </div>
</div>
</body>

</html>