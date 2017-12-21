<?php
$this->layout("session/view/question",
    [
        "question" => $question,
        "response" => $response,
    ]
);

// If a response has already been made, disable the input
$disabled = $response ? " disabled" : "";

$response = $response?$response->getResponse():"";
?>

<textarea style="width: 100%; resize: vertical;" rows="8" class="answer" id="answer" name="answer"<?=$disabled?>><?=$response?></textarea>