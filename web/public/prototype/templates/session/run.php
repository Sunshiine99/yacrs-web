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


<?php $this->push("end"); ?>
    <script src="<?=$config["baseUrl"]?>js/session/run.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<nav id="breadcrumb" aria-label="breadcrumb" role="navigation">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Session</a></li>
        <li class="breadcrumb-item"><a href="#">#1</a></li>
        <li class="breadcrumb-item active" aria-current="page">Run</li>
    </ol>
</nav>

<h1>Session #1</h1>
<h2 class="pull-left">Session Questions</h2>
<a href="#" class="btn btn-primary pull-right">Add Question</a>

<div>
    <ul class="list-group question-list" style="width:100%;">
        <?php
        for($i=1; $i<6; $i++):
            $time = time();
            $time = rand($time-60*60*24*31*12*2, $time);
            $created = date("d/m/Y H:i", $time);
            ?>
            <li class="list-group-item question-item">
                <div class="pull-left">
                    <span class="question-title">
                        <a href="#">Test Question <?=$i?></a>
                    </span><br>
                    <span class="question-date text-muted">
                        Created <?=$created?>
                    </span>
                </div>
                <div class="actions-confirm-delete">
                    <div class="btn-group pull-right actions" aria-label="Actions">
                        <button type="button" class="btn btn-light btn-light-border">
                            <i class="fa fa-play"></i> Make Active
                        </button>
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
        <?php endfor; ?>
    </ul>
</div>