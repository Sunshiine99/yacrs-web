<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('lib/forms.php');
require_once('lib/database.php');
include_once('corelib/mobile.php');
require_once('lib/shared_funcs.php');
include_once('lib/lti_funcs.php');

$template = new templateMerge($TEMPLATE);

// Create new instance of plates templating system
$templates = new League\Plates\Engine($CFG['templates']);

// Data for template
$data = [
    "CFG" => $CFG,
    "title" => $CFG['sitetitle'],
    "description" => $CFG['sitetitle'],
    "breadcrumbs" => new Breadcrumb(),
];

// Set page breadcrumbs
$data["breadcrumbs"]->addItem("YACRS", $CFG["baseUrl"]);
$data["breadcrumbs"]->addItem("Edit a session");

//die($templates->render("session/edit", $data));










// Check if user is logged in and if they are, load their details
$uinfo = checkLoggedInUser();

// If user is not logged in, forward them to home
if ($uinfo == false) {
    header("Location: index.php");
    die();
}

$esform = new editSession_form();

if ($esform->getStatus() == FORM_NOTSUBMITTED) {
    $esform->visible = true; // default to showing sessions.
}
//$esform->disable('questionMode');
//$esform->disable('defaultQuActiveSecs');
//$esform->disable('allowQuReview');
$esform->disable('maxMessagelength');
$esform->disable('allowTeacherQu');

// If session has been set get this session
if (requestSet('sessionID'))
    $thisSession = session::retrieve_session(requestInt('sessionID'));

else
    $thisSession = false;

switch ($esform->getStatus()) {
    case FORM_NOTSUBMITTED:
        if ($thisSession) {
            $esform->setData($thisSession);
            $esform->sessionID = $thisSession->id;
            if (isset($thisSession->extras['customScoring']))
                $esform->customScoring = $thisSession->extras['customScoring'];
            if (isset($thisSession->extras['allowFullReview']))
                $esform->allowFullReview = $thisSession->extras['allowFullReview'];
            $esform->teachers = implode(', ', $thisSession->getExtraTeacherIDs());
        } else {
            $esform->maxMessagelength = 140;
        }
        $template->pageData['mainBody'] = $esform->getHtml();
        break;
    case FORM_SUBMITTED_INVALID:
        $template->pageData['mainBody'] = $esform->getHtml();
        break;
    case FORM_SUBMITTED_VALID:
        if (!$thisSession) {
            $thisSession = new session();
            $thisSession->ownerID = $uinfo['uname'];
        }
        $esform->getData($thisSession);
        if (isset($esform->customScoring))
            $thisSession->extras['customScoring'] = $esform->customScoring;
        else
            $thisSession->extras['customScoring'] = false;
        $thisSession->extras['allowFullReview'] = $esform->allowFullReview;
        if ($thisSession->id > 0)
            $thisSession->update();
        else {
            $thisSession->created = time();
            $thisSession->id = $thisSession->insert();
        }
        $thisSession->updateExtraTeachers($esform->teachers);
        if (strlen($thisSession->courseIdentifier))
            enrolStudents($thisSession->id, $thisSession->courseIdentifier);
        header('Location:index.php?sessionID=' . $thisSession->id);
        break;
    case FORM_CANCELED:
        header('Location:index.php');
        break;
}
$template->pageData['logoutLink'] = loginBox($uinfo);

if (($thisSession !== false) && ($ltiSessionID = getLTISessionID()) && (isLTIStaff())) {
    $template->pageData['mainBody'] .= "<p>To use the teacher control app for this session login with username: <b>{$thisSession->id}</b> and password <b>" . substr($thisSession->ownerID, 0, 8) . "</b></p>";
}

echo $template->render();


?>
