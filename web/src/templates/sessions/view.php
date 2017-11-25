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

    if($this->exists("sessions/view/$type")) {
        $this->insert("sessions/view/$type", ["question" => $question]);
    }
    else {
        echo "Invalid Question Type";
    }
endif;
?>