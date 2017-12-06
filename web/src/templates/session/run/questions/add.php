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

<?php $this->push("head"); ?>
    <link rel="stylesheet" type="text/css" href="<?=$config["baseUrl"]?>css/session/run/edit.css" />
<?php $this->end(); ?>

<?php $this->push("postContent"); ?>
    <script src="<?=$config["baseUrl"]?>js/sessions/run/questions/edit-template.js"></script>
    <script src="<?=$config["baseUrl"]?>js/sessions/run/questions/edit.js"></script>
<?php $this->end(); ?>

<h2 class="page-section">
    Add Question
</h2>
<div class="form-group row">
    <label class="col-sm-2 control-label" for="questionType">Type</label>
    <div class="col-sm-10">
        <select id="questionType" name="questionType" class="form-control" tabindex="1">
            <option selected="1" value="mcq">Multiple Choice Question</option>
            <option value="text">Text Input</option>
            <option value="textlong">Long Text Input</option>
        </select>
    </div>
</div>

<form id="" action="." method="POST" class="form-horizontal">
    <input id="questionType" name="questionType" value="mcq" type="hidden">
    <input name="id" value="" type="hidden">
    <div class="form-group row">
        <label class="col-sm-2 control-label" for="question">Question</label>
        <div class="col-sm-10">
            <input class="form-control" name="question" id="mcQuestion" value="" size="80" type="text" tabindex="1">
        </div>
    </div>
    <div class="form-group row">
        <label for="definition" class="control-label col-sm-2">Choices</label>
        <div class="col-sm-10">
            <div id="add-more-choices" class="input-add-more-container" data-minimum-count="1">
                <?php for($i=1; $i<=4; $i++): ?>
                    <div class="input-group input-add-more-item">
                        <input id="mcq-choice-<?=$i?>" name="mcq-choice-<?=$i?>" class="form-control input-add-more-input" type="text" value="" tabindex="1">
                        <button class="delete btn btn-light btn-light-border input-add-more-input" type="button" tabindex="2">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </button>
                    </div>
                <?php endfor; ?>
            </div>
            <div id="add-more-button-container" class="col-sm-12 input-add-more-button" data-input-container-id="add-more-choices">
                <input class="submit btn btn-primary" name="submit" value="Create" type="submit" tabindex="1">
                <button class="btn btn-light btn-light-border input-add-more-input float-right" type="button">
                    Add Another Choice
                </button>
            </div>
        </div>
    </div>
</form>