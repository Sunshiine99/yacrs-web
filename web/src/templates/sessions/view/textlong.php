<?php
$this->layout("sessions/view/question",
    [
        "question" => $question,
        "response" => $response,
    ]
);

// If a response has already been made, disable the input
$disabled = $response ? " disabled" : "";
?>

<textarea style="width: 100%; resize: vertical;" rows="8" class="answer" id="answer" name="answer"<?=$disabled?>><?=$response->getResponse()?></textarea>