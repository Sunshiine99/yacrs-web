<?php
$this->layout("template",
    [
        "CFG" => $CFG,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "uinfo" => $uinfo,
    ]
);
?>

<form action="<?=$CFG["baseUrl"]?>/editsession.php" method="POST" class="form-horizontal">
    <input name="editSession_form_code" value="73e4b27a947d6e4f3a1c38c04af1a20f" type="hidden">
    <input name="sessionID" value="" type="hidden">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="title">Title</label>
        <div class="col-sm-8">
            <input class="form-control" name="title" id="title" value="" size="80" type="text">
        </div>
    </div>
    <input name="courseIdentifier" value="" type="hidden">
    <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4">
            <div class="checkbox">
                <label>
                    <input name="allowGuests" id="allowGuests" value="1" type="checkbox">Allow guest users (without login)</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4">
            <div class="checkbox">
                <label>
                    <input name="visible" id="visible" value="1" checked="1" type="checkbox">Display on user's available sessions list</label>
            </div>
        </div>
    </div>
    <fieldset>
        <legend>Question settings</legend>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="questionMode">Question control mode</label>
            <div class="col-sm-8">
                <select class="form-control" name="questionMode" id="questionMode">
                    <option value="0">Teacher led (one question at a time)</option>
                    <option value="1">Student paced</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="defaultQuActiveSecs">Default time limit for active questions (seconds, 0 for no limit).</label>
            <div class="col-sm-8">
                <input class="form-control" name="defaultQuActiveSecs" id="defaultQuActiveSecs" value="" size="8" type="text">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="checkbox">
                    <label>
                        <input name="allowQuReview" id="allowQuReview" value="1" type="checkbox">Allow review/change of answers while response open</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="checkbox">
                    <label>
                        <input name="allowFullReview" id="allowFullReview" value="1" type="checkbox">Allow students to view their answers after class.</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="customScoring">Custom scoring</label>
            <div class="col-sm-8">
                <select class="form-control" name="customScoring" id="customScoring">
                    <option selected="1" value="">None</option>
                    <option value="Quintins_scoring.php">Quintins_scoring</option>
                </select>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>Text/micro blogging settings</legend>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="ublogRoom">Micro blogging mode</label>
            <div class="col-sm-8">
                <select class="form-control" name="ublogRoom" id="ublogRoom">
                    <option value="0">None</option>
                    <option value="1">Full class</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="maxMessagelength">Maximum message length (characters)</label>
            <div class="col-sm-8">
                <input class="form-control" name="maxMessagelength" id="maxMessagelength" value="140" size="8" disabled="1" type="text">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="checkbox">
                    <label>
                        <input name="allowTeacherQu" id="allowTeacherQu" value="1" disabled="disabled" type="checkbox">Allow questions for the teacher?</label>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>Additional teachers</legend>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="teachers">Additional users who can run session (comma delimited list of user IDs)</label>
            <div class="col-sm-8">
                <input class="form-control" name="teachers" id="teachers" value="" size="80" type="text">
            </div>
        </div>
    </fieldset>
    <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4">
            <input class="submit btn btn-success" name="submit" value="Create" type="submit">
            <input class="submit btn btn-link" name="submit" value="Cancel" type="submit">
        </div>
    </div>
</form>