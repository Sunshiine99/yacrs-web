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
    <link rel="stylesheet" type="text/css" href="<?=$config["baseUrl"]?>css/sessions/run/run.css" />
<?php $this->end(); ?>

<h1 style="text-align:center;">
    Session ID: <?=$session->getSessionId()?>
</h1>
<p>
    <a href="<?=$config["baseUrl"]?>sessions/<?=$session->getSessionId()?>/members/">Active users (total users): IMPLEMENT ME (0)</a>
</p>
<h2>Session Questions</h2>

<?php if(sizeof($questions) === 0): ?>
    <p>No questions added yet.</p>
<?php else: ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Last Update</th>
                <th>Control</th>
                <th>Responses</th>
                <th>Edit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach($questions["questions"] as $question):

                $class = $question->isActive() ? " class='activeQuestion'" : "";
            ?>
                <tr<?=$class?>>
                    <td><?=$i?>.</td>
                    <td><?=$question->getQuestion()?></td>
                    <td><?=date("d/m/Y H:i", $question->getLastUpdate())?></td>
                    <td>
                        <form action="." method="POST">
                            <input name="field" value="control" type="hidden">
                            <input name="sqid" value="<?=$question->getSessionQuestionID()?>" type="hidden">
                            <?php if($question->isActive()): ?>
                                <input name="value" value="deactivate" type="hidden">
                                <input class="submit btn btn-link" name="submit" value="Close" type="submit">
                            <?php elseif(!$questions["active"]): ?>
                                <input name="value" value="activate" type="hidden">
                                <input class="submit btn btn-link" name="submit" value="Make Active" type="submit">
                            <?php endif; ?>
                        </form>
                    </td>
                    <td><a href="#">??? responses</a></td>
                    <td>
                        <a href="<?=$config["baseUrl"]?>sessions/<?=$session->getSessionId()?>/run/questions/<?=$question->getQuestionId()?>/">
                            <i class='fa fa-pencil'></i> Edit
                        </a>
                    </td>
                    <td>
                        <span class="feature-links">
                            <a href="#"><i class="fa fa-arrows"></i> Move</a>
                            <a href="#"><i class="fa fa-trash-o"></i> Delete</a>
                        </span>
                    </td>
                </tr>
            <?php
            $i++;
            endforeach;
            ?>
        </tbody>
    </table>

<?php endif; ?>

<a href="<?=$config["baseUrl"]?>sessions/<?=$session->getSessionId()?>/run/questions/new/" class="btn btn-primary">Add a Question</a>