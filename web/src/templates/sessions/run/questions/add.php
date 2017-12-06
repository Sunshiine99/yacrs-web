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

<?php $this->push("preContent"); ?>
<link rel="stylesheet" type="text/css" href="<?=$config["baseUrl"]?>css/sessions/run/questions/edit.css" />
<?php $this->end(); ?>

<?php $this->push("postContent"); ?>
<script src="<?=$config["baseUrl"]?>js/sessions/run/questions/edit-template.js"></script>
<script src="<?=$config["baseUrl"]?>js/sessions/run/questions/edit.js"></script>
<?php $this->end(); ?>
<h2 class="page-section">
    Add Question
</h2>
<form action="/editquestion.php" method="POST" class="form-horizontal">
    <input name="selectQuestionType_form_code" value="8acab4c527c7ff2adb0898459f63c1bd" type="hidden">
    <input name="sessionID" value="981" type="hidden">
    <div class="form-group">
        <label class="col-sm-2 control-label" for="qu">Type</label>
        <div class="col-sm-10">
            <select id="questionType" name="questionType" class="form-control" tabindex="1">
                <option selected="1" value="mcq">Multiple Choice Question</option>
                <option value="text">Text Input</option>
                <option value="textlong">Long Text Input</option>
            </select>
        </div>
    </div>
</form>

<div id="editQuestion"></div>