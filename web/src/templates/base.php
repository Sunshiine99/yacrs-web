<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->e($title) ?></title>
    <link rel="stylesheet" type="text/css" media="Screen" href="<?= $this->e($CFG["baseUrl"]) ?>html/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="Screen" href="<?= $this->e($CFG["baseUrl"]) ?>html/bootstrap-theme.css" />
    <link rel="stylesheet" type="text/css" media="Screen" href="<?= $this->e($CFG["baseUrl"]) ?>html/font-awesome.css" />
    <link rel="stylesheet" type="text/css" media="Screen" href="<?= $this->e($CFG["baseUrl"]) ?>html/yacrs-base.css" />
    <link rel="stylesheet" type="text/css" media="Screen" href="<?= $this->e($CFG["baseUrl"]) ?>html/yacrs-theme.css" />
</head>
<body>
<div id="pageHeader">
    <?=$this->section('header')?>
</div>
<div id="main">
    <div class="container">
        <?=$this->section('preContent')?>
        <div id="content">
            <?=$this->section('content')?>
        </div>
        <?=$this->section('postContent')?>
    </div>
</div>
<div class="footer" id="pageFooter">
    <?=$this->section('footer')?>
</div>
</body>
</html>