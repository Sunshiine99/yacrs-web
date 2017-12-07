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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="<?=$config["baseUrl"]?>js/sessions/run/questions/edit-template.js"></script>
<script src="<?=$config["baseUrl"]?>js/sessions/run/questions/edit.js"></script>
<script>
    $(document).ready(function() {
        var selected = $('#questionType').find(":selected").text();
        if(selected == "Multiple Choice Question"){
            mcqEditTemplate = mcqEditTemplate.replace("id=\"createButton\" name=\"submit\" value=\"Create\"", "id=\"createButton\" name=\"submit\" value=\"Edit\"");
            mcqEditTemplate = mcqEditTemplate.replace("id=\"mcQuestion\" value=\"\"", "id=\"mcQuestion\" value=\"<?=$question?>\"");
            mcqInit(<?=json_encode($choices)?>);
        }
        else if(selected == "Text Input"){
            textInit();
            textEditTemplate = textEditTemplate.replace("id=\"createButton\" name=\"submit\" value=\"Create\"", "id=\"createButton\" name=\"submit\" value=\"Edit\"");
            textEditTemplate = textEditTemplate.replace("id=\"textQuestion\" value=\"\"", "id=\"textQuestion\" value=\"<?=$question?>\"");
        }
        else{
            textInit(true);
            textEditTemplate = textEditTemplate.replace("id=\"createButton\" name=\"submit\" value=\"Create\"", "id=\"createButton\" name=\"submit\" value=\"Edit\"");
            textEditTemplate = textEditTemplate.replace("id=\"textQuestion\" value=\"\"", "id=\"textQuestion\" value=\"<?=$question?>\"");
        }
    });
    $(document).on("change", "#questionType", function () {
        var selected = $('#questionType').find(":selected").text();
        if(selected == "Multiple Choice Question"){
            mcqEditTemplate = mcqEditTemplate.replace("id=\"createButton\" name=\"submit\" value=\"Create\"", "id=\"createButton\" name=\"submit\" value=\"Edit\"");
            mcqEditTemplate = mcqEditTemplate.replace("id=\"mcQuestion\" value=\"\"", "id=\"mcQuestion\" value=\"<?=$question?>\"");
            mcqInit(<?=json_encode($choices)?>);
        }
        else if(selected == "Text Input"){
            textInit();
            textEditTemplate = textEditTemplate.replace("id=\"createButton\" name=\"submit\" value=\"Create\"", "id=\"createButton\" name=\"submit\" value=\"Edit\"");
            textEditTemplate = textEditTemplate.replace("id=\"textQuestion\" value=\"\"", "id=\"textQuestion\" value=\"<?=$question?>\"");
            $("#editQuestion").html(textEditTemplate);
        }
        else{
            textInit(true);
            textEditTemplate = textEditTemplate.replace("id=\"createButton\" name=\"submit\" value=\"Create\"", "id=\"createButton\" name=\"submit\" value=\"Edit\"");
            textEditTemplate = textEditTemplate.replace("id=\"textQuestion\" value=\"\"", "id=\"textQuestion\" value=\"<?=$question?>\"");
            $("#editQuestion").html(textEditTemplate);
        }
    });
</script>
<?php $this->end(); ?>

<h2 class="page-section">
    Edit Question
</h2>

<form action="/editquestion.php" method="POST" class="form-horizontal">
    <input name="selectQuestionType_form_code" value="8acab4c527c7ff2adb0898459f63c1bd" type="hidden">
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