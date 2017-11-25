<?php
//$question = new QuestionMcq();
?>
<h2 class="page-section">
    <?=$question->getQuestion();?>
</h2>
<form action="." method="POST">
    <?php
    $i = 1;
    foreach($question->getChoices() as $choice): ?>
        <input id="answer-<?=$i?>" type="radio" value="<?=$i?>" name="answer">
        <label for="answer-<?=$i?>"><?=$choice->getChoice()?></label><br>
    <?php
    $i++;
    endforeach;
    ?>
    <hr>
    <input name="submit" value="Submit Answer" class="btn btn-primary" type="submit">
</form>