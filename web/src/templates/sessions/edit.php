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

// Convert boolean values to html
$allowGuests            = $session->getAllowGuests()            ? " checked" : "";
$onSessionList          = $session->getOnSessionList()          ? " checked" : "";
$allowModifyAnswer      = $session->getAllowModifyAnswer()      ? " checked" : "";
$allowQuestionReview    = $session->getAllowQuestionReview()    ? " checked" : "";
$classDiscussionEnabled = $session->getClassDiscussionEnabled() ? " checked" : "";

$submitText = $session->getSessionID() ? "Save" : "Create";

?>

<?php $this->push("preContent"); ?>
    <?php if($session->getSessionID()): ?>
        <a href="<?=$config["baseUrl"]?>sessions/<?=$session->getSessionID()?>" class="btn btn-primary pull-right">Run Session</a>
    <?php endif; ?>
<?php $this->end(); ?>

<form action="." method="POST" class="form-horizontal">
    <input name="sessionID" value="<?=$session->getSessionID()?>" type="hidden">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="title">Title</label>
        <div class="col-sm-8">
            <input class="form-control" name="title" id="title" value="<?=$session->getTitle()?>" size="80" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="courseIdentifier">Course Identifier (to import classlist)</label>
        <div class="col-sm-8">
            <input class="form-control" name="courseID" id="courseID" value="<?=$session->getCourseID()?>" size="20" type="text">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4">
            <div class="checkbox">
                <label>
                    <input name="allowGuests" id="allowGuests" value="1" type="checkbox"<?=$allowGuests?>>
                    Allow guest users (without login)</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4">
            <div class="checkbox">
                <label>
                    <input name="onSessionList" id="onSessionList" value="1" type="checkbox"<?=$onSessionList?>>
                    Display on user's available sessions list</label>
            </div>
        </div>
    </div>
    <fieldset>
        <legend>Question settings</legend>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="questionControlMode">Question control mode</label>
            <div class="col-sm-8">
                <select class="form-control" name="questionControlMode" id="questionControlMode">
                    <option value="0"<?=$session->getQuestionControlMode()==0 ? " selected" : ""?>>
                        Teacher led (one question at a time)
                    </option>
                    <option value="1"<?=$session->getQuestionControlMode()==1 ? " selected" : ""?>>
                        Student paced
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="defaultTimeLimit">Default time limit for active questions
                (seconds, 0 for no limit).</label>
            <div class="col-sm-8">
                <input class="form-control" name="defaultTimeLimit" id="defaultTimeLimit" value="<?=$session->getDefaultTimeLimit()?>" size="8"
                       type="text">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="checkbox">
                    <label>
                        <input name="allowModifyAnswer" id="allowModifyAnswer" value="1" type="checkbox"<?=$allowModifyAnswer?>>
                        Allow review/change of answers while response open</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="checkbox">
                    <label>
                        <input name="allowQuestionReview" id="allowQuestionReview" value="1" type="checkbox"<?=$allowQuestionReview?>>
                        Allow students to view their answers after class.</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="customScoring">Custom scoring</label>
            IMPLEMENT ME
        </div>
    </fieldset>
    <fieldset>
        <legend>Class Discussion</legend>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="checkbox">
                    <label>
                        <input name="classDiscussionEnabled" id="classDiscussionEnabled" value="1" type="checkbox"<?=$classDiscussionEnabled?>>
                        Enabled</label>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>Additional Users</legend>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="teachers">Additional users who can run session (comma delimited
                list of user IDs)</label>
            <div class="col-sm-8">
                <input class="form-control" name="additionalUsersCsv" id="additionalUsersCsv" value="<?=$session->getAdditionalUsersCsv()?>" size="80" type="text">
            </div>
        </div>
    </fieldset>
    <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4">
            <input class="submit btn btn-success" name="submit" value="<?=$submitText?>" type="submit">
            <input class="submit btn btn-link" name="submit" value="Cancel" type="submit">
        </div>
    </div>
</form>