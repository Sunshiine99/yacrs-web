<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $session Session
 */

$title = $session->getSessionID() ? "Edit Session" : "New Session";

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
$title = $session->getSessionID() ? "Edit Session" : "New Session";

?>

<?php $this->push("head"); ?>
    <link href="<?=$this->e($config["baseUrl"])?>css/session/edit.css" rel="stylesheet">
<?php $this->stop(); ?>

<?php $this->push("end"); ?>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/edit.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<div class="row page-header">
    <div class="col-sm-12">
        <div class="float-left">
            <h1><?=$this->e($title)?></h1>
        </div>
        <?php if($session->getSessionID()): ?>
            <div class="float-right">
                <a href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($session->getSessionID())?>/run/" class="btn btn-primary pull-right">Run Session</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<form action="." method="POST" class="form-horizontal" style="display:block; width: 100%;">
    <input name="sessionID" value="<?=$this->e($session->getSessionID())?>" type="hidden">
    <div class="form-group row">
        <label class="col-sm-3 control-label" for="title">Title</label>
        <div class="col-sm-9">
            <input class="form-control" name="title" id="title" value="<?=$this->e($session->getTitle())?>" size="80" type="text" placeholder="Title">
        </div>
    </div>
    <div class="form-group row advanced">
        <label class="col-sm-3 control-label" for="courseIdentifier">Course Identifier</label>
        <div class="col-sm-9">
            <input class="form-control" name="courseID" id="courseID" value="<?=$this->e($session->getCourseID())?>" size="20" type="text" placeholder="Course Identifier (To Import Class List)">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3 offset-sm-3">
            <div class="checkbox">
                <label>
                    <input type="hidden" value="0" name="allowGuests">
                    <input name="allowGuests" id="allowGuests" value="1" type="checkbox"<?=$allowGuests?>>
                    Allow Anonymous Guest Users</label>
            </div>
        </div>
        <div class="col-sm-3 advanced">
            <div class="checkbox">
                <label>
                    <input type="hidden" value="0" name="onSessionList">
                    <input name="onSessionList" id="onSessionList" value="1" type="checkbox"<?=$onSessionList?>>
                    Display On User's Session List
                </label>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="checkbox">
                <label>
                    <input type="hidden" value="0" name="classDiscussionEnabled">
                    <input name="classDiscussionEnabled" id="classDiscussionEnabled" value="1" type="checkbox"<?=$classDiscussionEnabled?>>
                    Enable Class Discussion
                </label>
            </div>
        </div>
    </div>
    <fieldset>
        <legend class="advanced">Question settings</legend>
        <div class="form-group row">
            <label class="col-sm-3 control-label" for="questionControlMode">
                Question Control Mode
                <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="" data-original-title="
                      <h1>Teacher Led</h1>
                      Only one question can be shown to students at any one time and this question is controlled by
                      the teacher throughout the class.

                      <h1>Student Paced</h1>
                      Multiple questions can be shown to students at once and they can work through them at their
                      own pace.">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
            </label>
            <div class="col-sm-9">
                <select class="form-control" name="questionControlMode" id="questionControlMode">
                    <option value="0"<?=$session->getQuestionControlMode()==0 ? " selected" : ""?>>
                        Teacher Led
                    </option>
                    <option value="1"<?=$session->getQuestionControlMode()==1 ? " selected" : ""?>>
                        Student Paced
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group row advanced">
            <label for="defaultTimeLimit" class="col-sm-3 col-form-label">Default Time Limit</label>
            <div class="col-sm-2">
                <label class="form-check-label">
                    <input id="defaultTimeLimitEnable" class="form-check-input" value="" type="checkbox"<?=$session->getDefaultTimeLimit()!=0?" checked":""?>>
                    Enable
                </label>
            </div>
            <div class="col-sm-7">
                <input class="form-control" name="defaultTimeLimit" id="defaultTimeLimit" value="<?=$this->e($session->getDefaultTimeLimit())?>" size="8"
                       type="text" placeholder="Default Time Limit"<?=$session->getDefaultTimeLimit()==0?" disabled":""?>>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 offset-sm-3">
                <div class="checkbox">
                    <label>
                        <input type="hidden" value="0" name="allowModifyAnswer">
                        <input name="allowModifyAnswer" id="allowModifyAnswer" value="1" type="checkbox"<?=$allowModifyAnswer?>>
                        Allow students to change their answer</label>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="checkbox">
                    <label>
                        <input type="hidden" value="0" name="allowQuestionReview">
                        <input name="allowQuestionReview" id="allowQuestionReview" value="1" type="checkbox"<?=$allowQuestionReview?>>
                        Allow Students to view their answers after class</label>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="advanced">
        <legend>Additional Users</legend>
        <div class="form-group row">
            <label class="col-sm-3 control-label" for="teachers">Additional users who can run session (comma delimited
                list of user IDs)</label>
            <div class="col-sm-9">
                <input class="form-control" name="additionalUsersCsv" id="additionalUsersCsv" value="<?=$this->e($session->getAdditionalUsersCsv())?>" size="80" type="text">
            </div>
        </div>
    </fieldset>
    <div class="form-group row">
        <div class="col-sm-9 offset-sm-3">
            <input class="submit btn btn-primary" name="submit" value="<?=$submitText?>" type="submit">
            <a onclick="window.history.back();" class="submit btn btn-light btn-light-border">Cancel</a>

            <div class="pull-right">
                <a id="view-advanced-settings" class="btn btn-light btn-light-border">
                    View Advanced Settings
                </a>
                <a id="hide-advanced-settings" class="btn btn-light btn-light-border">
                    Hide Advanced Settings
                </a>
            </div>
        </div>
    </div>
</form>

<style>

    .tooltip {
        margin-left: 8px;
    }

    .tooltip h1 {
        font-size: 18px;
        margin: 0;
        margin-top: 10px;
        padding: 0;
        font-weight: bolder;
    }


    .form-check-label {
        margin-top: 3px;
    }

    .btn.cancel {
        margin-left: 10px;
    }

    .delete.btn {
        border: 1px solid #ced4da;
    }

    .advanced {
        display: none;
    }
</style>