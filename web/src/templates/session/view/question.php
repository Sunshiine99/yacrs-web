<h2 class="page-section">
    <?=$question->getQuestion();?>
</h2>
<form action="." method="POST">
    <input name="sessionQuestionID" id="sessionQuestionID" value="<?=$question->getSessionQuestionID()?>" type="hidden">

    <?=$this->section('content')?>

    <?php
    $class = "";
    if($response) {
        $class = " display-none";
    }
    ?>
    <hr>
    <input name="submit" value="Submit Answer" class="answer-submit btn btn-primary <?=$class?>" type="submit">
    <?php if($response): ?>
        <button id="answer-update" type="button" class="btn btn-success">
            Update Answer
        </button>
        <a href="." class="btn btn-link pull-right">
            Continue to Next Question ›
        </a>
    <?php endif; ?>
</form>