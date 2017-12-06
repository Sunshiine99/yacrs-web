<?php
$this->layout("session/view/question",
    [
        "question" => $question,
        "response" => $response,
    ]
);

// If a response has already been made, disable the input
$disabled = $response ? " disabled" : "";
?>

<input style="width: 100%" class="answer" id="answer" type="text" value="<?=$response->getResponse()?>" name="answer"<?=$disabled?>>