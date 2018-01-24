<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $session Session
 * @var $questions Question[][]
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
?>

<?php $this->push("head"); ?>
    <link rel="stylesheet" href="<?=$this->e($config["baseUrl"])?>css/session/run/run.css">
<?php $this->stop(); ?>

<?php $this->push("end"); ?>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/run.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<div class="page-header">
    <h1 class="row">
        <div class="col-sm-9">
            <?=$session->getTitle() ? $this->e($session->getTitle()) : "&nbsp;"?>
        </div>
        <div class="col-sm-3">
            <a href="<?=$config["baseUrl"]?>session/<?=$this->e($session->getSessionIdentifier())?>/edit/" class="btn btn-light btn-light-border pull-right width-xs-full">Edit Session</a>
        </div>
    </h1>
</div>
<div class="row">
    <div class="col-sm-9">
        <h2 class="pull-left">Session Questions</h2>
    </div>
    <div class="col-sm-3">
        <a href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($session->getSessionIdentifier())?>/run/question/new/" class="btn btn-primary pull-right width-xs-full margin-xs-bottom-10">
            Add Question
        </a>
    </div>
</div>
<div>
    <ul class="list-group question-list" style="width:100%;" data-question-control-mode="<?=$this->e($session->getQuestionControlMode())?>">
        <li class="no-questions">
            No Questions Found
        </li>
        <?php
        $i = 1;
        $qi = count($questions["questions"]);
        foreach($questions["questions"] as $question):
            $class = $question->isActive() ? " active-question" : "";

            ?>
            <li class="list-group-item question-item<?=$this->e($class)?>">
                <div class="question-number pull-left">
                    <?=$qi?>.
                </div>
                <div class="pull-left details">
                    <span class="question-title">
                        <?=$question->getQuestion() ? $this->e($question->getQuestion()) : $question->getTypeDisplay() . " Question"?>
                    </span><br>
                    <span class="question-date text-muted">
                        Created <?=date("d/m/Y H:i", $question->getCreated())?>
                    </span>
                </div>
                <div class="actions-confirm-delete">
                    <div class="btn-group pull-right actions" aria-label="Actions">
                        <button type="button" class="btn btn-light btn-light-border deactivate" data-session-identifier="<?=$this->e($session->getSessionIdentifier())?>" data-session-question-id="<?=$this->e($question->getSessionQuestionID())?>">
                            <i class='fa fa-stop'></i> Close
                        </button>
                        <button type="button" class="btn btn-light btn-light-border activate" data-session-identifier="<?=$this->e($session->getSessionIdentifier())?>" data-session-question-id="<?=$this->e($question->getSessionQuestionID())?>">
                            <i class='fa fa-play'></i> Activate
                        </button>

                        <a href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($session->getSessionIdentifier())?>/run/question/<?=$this->e($question->getSessionQuestionID())?>/response/" class="btn btn-light btn-light-border">
                            <i class="fa fa-eye"></i> Responses
                        </a>
                        <a class="btn btn-light btn-light-border" href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($session->getSessionIdentifier())?>/run/question/<?=$this->e($question->getSessionQuestionID())?>/">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn btn-light btn-light-border delete">
                            <i class="fa fa-trash-o"></i> Delete
                        </button>
                    </div>
                    <div class="btn-group pull-right confirm-delete" aria-label="Confirm Delete">
                        <button type="button" class="btn btn-danger btn-danger-border confirm" data-session-identifier="<?=$this->e($session->getSessionIdentifier())?>" data-session-question-id="<?=$this->e($question->getSessionQuestionID())?>">
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
            $qi--;
        endforeach;
        ?>
    </ul>
</div>
<div class="row">
    <div class="col-sm-12">
        <br>
        <h2>Export</h2>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <a href="<?=$config["baseUrl"]?>session/1/run/export/" class="btn btn-primary">Export as Spreadsheet</a>
    </div>
</div>