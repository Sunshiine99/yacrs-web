<?php
/**
 * @var $question Question|QuestionMcq|QuestionText|QuestionTextLong
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user
    ]
);

// Whether this is a new question
$new = !isset($question);

// Generic text to use in page the "new question" and "edit question" pages
$newEditText = $new ? "New" : "Edit";
$saveText = $new ? "Create" : "Save";

// If this is a new question, add default number of MCQ choices
if($new) {
    $choices = array_pad([], 4, "");
}

// Otherwise not a new question
else {

    // Array of possible choices
    $choices = [];

    // If MCQ add choices to array
    if(get_class($question) === "QuestionMcq")
        foreach ($question->getChoices() as $choice)
            array_push($choices, $choice->getChoice());

    // If no choices have been added, add one
    if(count($choices) == 0) {
        array_push($choices, "");
    }
}

?>

<?php $this->push("head"); ?>
    <link rel="stylesheet" type="text/css" href="<?=$config["baseUrl"]?>css/session/run/question/edit.css" />
<?php $this->end(); ?>

<?php $this->push("end"); ?>
    <script src="<?=$config["baseUrl"]?>js/session/run/question/edit.js"></script>
<?php $this->end(); ?>

<h2 class="page-section">
    <?=$newEditText?> Question
</h2>

<form id="" action="." method="POST" class="form-horizontal<?=$new?" new":""?>">

    <?php // If this is a new question, display question type selection ?>
    <?php if($new): ?>
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

    <?php // If this is editing an existing question, add type and session question id as hidden input field ?>
    <?php else: ?>
        <input type="hidden" name="questionType" value="<?=$question->getType()?>">
        <input type="hidden" name="sqid" value="<?=$question->getSessionQuestionID()?>">
    <?php endif; ?>

    <div class="form-group row" id="questions-row">
        <label class="col-sm-2 control-label" for="question">Question</label>
        <div class="col-sm-10">
            <input class="form-control" name="question" id="mcQuestion" value="<?=isset($question)?$question->getQuestion():""?>" size="80" type="text" tabindex="1">
        </div>
    </div>

    <?php if($new || get_class($question) == "QuestionMcq"): ?>
        <div id="question-mcq" class="form-group row question">
            <label for="definition" class="control-label col-sm-2">
                <span>Choices</span>
            </label>
            <div class="col-sm-10">
                <div id="add-more-choices" class="input-add-more-container" data-minimum-count="1">
                    <?php $i = 0; ?>
                    <?php foreach ($choices as $choice): ?>
                        <div class="input-group input-add-more-item">
                            <input id="mcq-choice-<?=$i?>" name="mcq-choice-<?=$i?>" class="form-control input-add-more-input" type="text" value="<?=$choice?>" tabindex="1">
                            <button class="delete btn btn-light btn-light-border input-add-more-input" type="button" tabindex="2">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </div>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </div>
                <div id="add-more-button-container" class="col-sm-12 input-add-more-button" data-input-container-id="add-more-choices">
                    <input class="submit btn btn-primary" name="submit" value="<?=$saveText?>" type="submit" tabindex="1">
                    <button class="btn btn-light btn-light-border input-add-more-input float-right" type="button">
                        Add Another Choice
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if($new || in_array(get_class($question), ["QuestionText", "QuestionTextLong"])): ?>
        <div id="question-text" class="form-group row question">
            <div class="col-sm-10 offset-sm-2">
                <input class="submit btn btn-primary" name="submit" value="<?=$saveText?>" type="submit" tabindex="1">
            </div>
        </div>
    <?php endif; ?>
    <?php if($new || !in_array(get_class($question), ["QuestionMcq", "QuestionText", "QuestionTextLong"])): ?>
        <div id="question-other" class="form-group row question">
            Question type may not be supported.
        </div>
    <?php endif; ?>
</form>