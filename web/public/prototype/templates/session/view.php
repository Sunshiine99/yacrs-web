<?php
$this->layout("template",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user
    ]
);
?>

<nav id="breadcrumb" aria-label="breadcrumb" role="navigation">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Session</a></li>
        <li class="breadcrumb-item active" aria-current="page">#1</li>
    </ol>
</nav>

<h2>What colour is the sky?</h2>
<form action="." method="POST">
    <input id="answer-1" type="radio" value="1" name="answer">
    <label for="answer-1">Blue</label><br>
    <input id="answer-2" type="radio" value="2" name="answer">
    <label for="answer-2">Green</label><br>
    <input id="answer-3" type="radio" value="3" name="answer">
    <label for="answer-3">Yellow</label><br>
    <input id="answer-4" type="radio" value="4" name="answer">
    <label for="answer-4">Red</label><br>
    <hr>
    <input name="submit" value="Submit Answer" class="btn btn-primary" type="submit">
</form>