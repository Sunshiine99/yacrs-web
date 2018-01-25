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
    <script src="<?=$config["baseUrl"]?>js/session/edit.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<nav id="breadcrumb" aria-label="breadcrumb" role="navigation">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Session</a></li>
        <li class="breadcrumb-item active" aria-current="page">New</li>
    </ol>
</nav>
<form>
    <div class="form-group row">
        <label for="title" class="col-sm-3 col-form-label">Title</label>
        <div class="col-sm-9">
            <input id="title" name="title" placeholder="Title" class="form-control" type="text">
        </div>
    </div>
    <div class="form-group row">
        <label for="title" class="col-sm-3 col-form-label">Course Identifier</label>
        <div class="col-sm-9">
            <input id="title" name="title" placeholder="Course Identifier (To Import Class List)" class="form-control" type="text">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-sm-3 col-sm-3">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="">
                Allow Anonymous Guest Users
            </label>
        </div>
        <div class="col-sm-3">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="" checked>
                Display On User's Session List
            </label>
        </div>
        <div class="col-sm-3">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="" checked>
                Enable Class Discussion
            </label>
        </div>
    </div>
    <h2>Question Settings</h2>
    <div class="form-group row">
        <label for="questionMode" class="col-sm-3 col-form-label">
            Question Control Mode
            <a href="#" data-toggle="tooltip" data-placement="right" data-html="true"
               title="<h1>Teacher Led</h1>
                      Only one question can be shown to students at any one time and this question is controlled by
                      the teacher throughout the class.

                      <h1>Student Paced</h1>
                      Multiple questions can be shown to students at once and they can work through them at their
                      own pace.">
                <i class="fa fa-question-circle" aria-hidden="true"></i>
            </a>
        </label>
        <div class="col-sm-9">
            <select class="form-control" name="questionMode" id="questionMode">
                <option value="0">
                    Teacher Led
                </option>
                <option value="1">
                    Student Paced
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="defaultQuActiveSecs" class="col-sm-3 col-form-label">Default Time Limit</label>
        <div class="col-sm-2">
            <label class="form-check-label">
                <input id="defaultQuActiveSecsEnable" class="form-check-input" type="checkbox" value="">
                Enable
            </label>
        </div>
        <div class="col-sm-7">
            <input id="defaultQuActiveSecs" name="defaultQuActiveSecs" placeholder="Default Time Limit" class="form-control" type="text" value="0" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label for="questionMode" class="col-sm-3 col-form-label">
            Custom Scoring
        </label>
        <div class="col-sm-9">
            <select class="form-control" name="questionMode" id="questionMode">
                <option value="0">
                    None
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-sm-3 col-sm-4">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="">
                Allow students to change their answer
            </label>
        </div>
        <div class="col-sm-5">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="" checked>
                Allow Students to view their answers after class
            </label>
        </div>
    </div>
    <h2>Additional Users</h2>
    <div class="form-group row">
        <label for="title" class="col-sm-3 col-form-label">
            Usernames
            <a href="#" data-toggle="tooltip" data-placement="right" data-html="true"
               title="Additional users are able to control this session in the same way the owner (you) can">
                <i class="fa fa-question-circle" aria-hidden="true"></i>
            </a>
        </label>
        <div id="add-more-additional-users" class="col-sm-9 input-add-more-container">
            <div class="input-group input-add-more-item">
                <input id="title" name="title" placeholder="Username" class="form-control input-add-more-input" type="text" value="123">
                <button class="delete btn btn-light input-add-more-input" type="button">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </button>
            </div>
            <div class="input-group input-add-more-item">
                <input id="title" name="title" placeholder="Username" class="form-control input-add-more-input" type="text">
                <button class="delete btn btn-light input-add-more-input" type="button">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="col-sm-12 input-add-more-button" data-input-container-id="add-more-additional-users">
            <button class="btn btn-primary input-add-more-input float-right" type="button">
                Add Another User
            </button>
        </div>
    </div>

    <hr>
    <div class="form-group row">
        <div class="offset-sm-3 col-sm-9">
            <input class="submit btn btn-primary" name="submit" value="Create" type="submit">
            <a href="#" class="btn btn-light cancel">Cancel</a>
        </div>
    </div>
</form>

<?php $this->push("end"); ?>
    <script>

    </script>
<?php $this->end(); ?>

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
</style>