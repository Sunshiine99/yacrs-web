<?php
$this->layout("template",
    [
        "config" => $config,
        "title" => $session->getTitle() . " (#" . $session->getSessionId() . ")",
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert,
    ]
);
?>

<?php $this->push("end"); ?>
    <script src="<?=$config["baseUrl"]?>js/sessions/view.js"></script>
<?php $this->end(); ?>

<?php
// If no question active
if($question === null):
?>
    <div class="alert alert-warning">
        No active question.
    </div>
    <a class="pull-right" href=".">Refresh</a>

<?php else:
    $type = $question->getType();

    if($this->exists("session/view/$type")) {
        $this->insert("session/view/$type", ["question" => $question, "response" => $response]);
    }
    else {
        echo "Invalid Question Type";
    }
endif; ?>