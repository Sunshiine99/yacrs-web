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
    <link rel="stylesheet" href="<?=$config["baseUrl"]?>css/session/run/run.css">
<?php $this->stop(); ?>

<?php $this->push("end"); ?>
    <script src="<?=$config["baseUrl"]?>js/session/run.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<div class="page-header">
    <h1><?=$session->getTitle()?></h1>
</div>
<h2 class="pull-left">Session Questions</h2>
<a href="<?=$config["baseUrl"]?>session/<?=$session->getSessionID()?>/run/question/new/" class="btn btn-primary pull-right">
    Add Question
</a>

<div>
    <ul class="list-group question-list" style="width:100%;">
        <li class="no-questions">
            No Questions Found
        </li>
        <?php
        $i = 1;
        foreach($questions["questions"] as $question):
            $class = $question->isActive() ? " active-question" : "";

            ?>
            <li class="list-group-item question-item<?=$class?>">
                <div class="pull-left">
                    <span class="question-title">
                        <?=$question->getQuestion()?>
                    </span><br>
                    <span class="question-date text-muted">
                        Created <?=date("d/m/Y H:i", $question->getCreated())?>
                    </span>
                </div>
                <div class="actions-confirm-delete">
                    <div class="btn-group pull-right actions" aria-label="Actions">

                        <?php // If the question is active, display the close button ?>
                        <?php if($question->isActive()): ?>
                            <button onclick="$.redirectPost('.', {field: 'control', value: 'deactivate', sqid: '<?=$question->getSessionQuestionID()?>'});" type="button" class="btn btn-light btn-light-border">
                                <i class='fa fa-stop'></i> Close
                            </button>

                        <?php // If the question is not active and no questions are active, display the make active button ?>
                        <?php elseif(!$questions["active"]): ?>
                            <button onclick="$.redirectPost('.', {field: 'control', value: 'activate', sqid: '<?=$question->getSessionQuestionID()?>'});" type="button" class="btn btn-light btn-light-border">
                                <i class='fa fa-play'></i> Make Active
                            </button>
                        <?php endif; ?>

                        <button type="button" class="btn btn-light btn-light-border">
                            <i class="fa fa-eye"></i> View Responses
                        </button>
                        <button type="button" class="btn btn-light btn-light-border">
                            <i class="fa fa-pencil"></i> Edit
                        </button>
                        <button type="button" class="btn btn-light btn-light-border delete">
                            <i class="fa fa-trash-o"></i> Delete
                        </button>
                    </div>
                    <div class="btn-group pull-right confirm-delete" aria-label="Confirm Delete">
                        <button type="button" class="btn btn-danger btn-danger-border confirm">
                            <i class="fa fa-check"></i> Confirm
                        </button>
                        <button type="button" class="btn btn-light btn-light-border cancel">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            </li>
            <?php
            $i++;
        endforeach;
        ?>
    </ul>
</div>