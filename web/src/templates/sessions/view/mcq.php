<?php
//$question = new QuestionMcq();
?>
<h2 class="page-section">
    <?=$question->getQuestion();?>
</h2>
<form action="." method="POST">
    <input name="sessionQuestionID" id="sessionQuestionID" value="<?=$question->getSessionQuestionID()?>" type="hidden">
    <?php

    // If a response has already been made, disable the radio buttons
    $disabled = $response ? " disabled" : "";

    $i = 1;
    foreach($question->getChoices() as $choice): ?>
        <?php
        $checked = "";
        if($response) {
            $checked = $choice->getChoiceID()==$response->getResponse() ? " checked" : "";
        }
        ?>
        <input class="answer" id="answer-<?=$i?>" type="radio" value="<?=$i?>" name="answer"<?=$checked?><?=$disabled?>>
        <label for="answer-<?=$i?>"><?=$choice->getChoice()?></label><br>
    <?php
    $i++;
    endforeach;
    ?>
    <hr>

    <input name="submit" value="Submit Answer" class="answer-submit btn btn-primary display-none" type="submit">
</form>