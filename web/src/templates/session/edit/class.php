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
        function exitClassMode(sessionIdentifier) {
            const electron = require('electron');
            let {ipcRenderer} = electron;
            ipcRenderer.send("exitClassMode");
        }
    </script>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-1">
            <i class="fa fa-angle-double-left"></i>
        </div>
        <div class="col-md-1">
            <i class="fa fa-angle-double-right"></i>
        </div>
        <div class="col-md-1">
            <a href="#" onclick="exitClassMode()">
                <i class="fa fa-power-off"></i>
            </a>
        </div>
        <div class="col-md-1">
            <i class="fa fa-chart-bar"></i>
        </div>
        <div class="col-md-1">
            <i class="fa fa-chart-pie"></i>
        </div>
    </div>
</div>
</body>

</html>