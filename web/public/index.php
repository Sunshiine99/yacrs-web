<?php
/*****************************************************************************
YACRS Copyright 2013-2015, The University of Glasgow.
Written by Niall S F Barr (niall.barr@glasgow.ac.uk, niall@nbsoftware.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*****************************************************************************/

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('lib/database.php');
require_once('lib/forms.php');
require_once('lib/shared_funcs.php');
include_once('corelib/mobile.php');
include_once('lib/lti_funcs.php');

// Create new instance of OLD templating system
$template_old = new templateMerge($TEMPLATE);

// Create new instance of plates templating system
$templates = new League\Plates\Engine($CFG['templates']);

// Data for template
$data = [
    "CFG" => $CFG,
    "title" => $CFG['sitetitle'],
    "description" => $CFG['sitetitle'],
    "breadcrumbs" => new Breadcrumb(),
];

$loginError = '';

// Check if user is logged in and if they are, load their details
$uinfo = checkLoggedInUser(true, $loginError);

// OLD templating system
$template_old->pageData['pagetitle'] = $CFG['sitetitle'];
$template_old->pageData['homeURL'] = $_SERVER['PHP_SELF'];
$template_old->pageData['breadcrumb'] = $CFG['breadCrumb'];
$template_old->pageData['breadcrumb'] .= '<li>YACRS</li>';
$template_old->pageData['breadcrumb'] .= '</ul>';

// Set page breadcrumbs
$data["breadcrumbs"]->addItem("YACRS");

// If user is not logged in, render the login page
if($uinfo==false)
    die($templates->render("login", $data));

// Otherwise, user is logged in
else
{
    $thisSession = requestSet('sessionID') ? session::retrieve_session(requestInt('sessionID')):false;
    if($thisSession)
    {
        if(checkPermission($uinfo, $thisSession))
        {
            $template_old->pageData['mainBody'] .= "<a href='runsession.php?sessionID={$thisSession->id}'>Run session</a>";
            header("Location: runsession.php?sessionID={$thisSession->id}");
        }
        elseif(($thisSession->currentQuestion==0)&&($thisSession->ublogRoom>0))
        {
            $template_old->pageData['mainBody'] .= "<a href='chat.php?sessionID={$thisSession->id}'>Join session</a>";
            header("Location: chat.php?sessionID={$thisSession->id}");
        }
        else
        {
            $template_old->pageData['mainBody'] .= "<a href='vote.php?sessionID={$thisSession->id}'>Join session</a>";
            header("Location: vote.php?sessionID={$thisSession->id}");
        }
    }
    elseif($ltiSessionID = getLTISessionID())
    {
        if(isLTIStaff())
	    {
	        $template_old->pageData['mainBody'] .= '<ul>';
            $s = session::retrieve_session($ltiSessionID);
            if($s !== false)
            {
	            $ctime = strftime("%A %e %B %Y at %H:%M", $s->created);
	            $template_old->pageData['mainBody'] .= "<li><p class='session-title'><a href='runsession.php?sessionID={$s->id}'>{$s->title}</a><span class='user-badge session-id'><i class='fa fa-hashtag'></i> {$s->id}</span></p><p class='session-details'> Created $ctime</p><a href='editsession.php?sessionID={$s->id}'>Edit</a> <a href='confirmdelete.php?sessionID={$s->id}'>Delete</a></li>";
	            //$template_old->pageData['mainBody'] .= "<li>Session number: <b>{$s->id}</b> <a href='runsession.php?sessionID={$s->id}'>{$s->title}</a> (Created $ctime) <a href='editsession.php?sessionID={$s->id}'>Edit</a> <a href='confirmdelete.php?sessionID={$s->id}'>Delete</a></li>";
                $template_old->pageData['mainBody'] .= "<li>To use the teacher control app for this session login with username: <b>{$s->id}</b> and password <b>".substr($s->ownerID, 0, 8)."</b></li>";

            }
            else
            {
                $template_old->pageData['mainBody'] .= "<li>No session found for this LTI link. To create a new session return to the VLE/LMS and click the link again.</li>";
            }
	        $template_old->pageData['mainBody'] .= '</ul>';
	    }
        else
        {
            $template_old->pageData['mainBody'] .= "<a href='vote.php?sessionID={$thisSession->id}'>Join session</a>";
            header("Location: vote.php?sessionID={$thisSession->id}");
        }
    }
    else
    {

        // Array of sessions that the user has access to modify as a staff member
        $staffSessions = array();

        // If the user can create sessions, load a list of the sessions the user has access to
        if($uinfo['sessionCreator'])
        {

            // Load staff sessions
            $staffSessions = session::retrieve_session_matching('ownerID', $uinfo['uname']);

            // If no sessions loaded, use an empty array
            if($staffSessions === false)
                $staffSessions = array();

            // Merge my sessions with those which I have access to
            $staffSessions = array_merge($staffSessions, session::teacherExtraSessions($uinfo['uname']));
        }

        // Load user sessions
        $slist = sessionMember::retrieve_sessionMember_matching('userID', $uinfo['uname']);

        // Array of
        $sessions = array();

        // If slist array is set
        if($slist)
        {

            // Loop for every session in slist
            foreach($slist as $s)
            {

                // Get session from slist item
                $sess = session::retrieve_session($s->session_id);

                // If session exists and it is visisble, add it to the array of sessions to show
                if(($sess)&&($sess->visible))
                    $sessions[] = $sess;
            }
        }


        $data["staffSessions"] = $staffSessions;
        $data["sessions"] = $sessions;
        $data["uinfo"] = $uinfo;


        die($templates->render("home", $data));







        // Session code input box
	    $template_old->pageData['mainBody'] = sessionCodeinput();

	    // If the user can create sessions
	    if($uinfo['sessionCreator'])
	    {

	        // Create a new session button
	        $template_old->pageData['mainBody'] .= "<div class='row'><div class='col-sm-8 col-sm-push-4'><a class='btn btn-primary' href='editsession.php'><i class='fa fa-plus-circle'></i> Create a new clicker session</a></div></div>";

	        // Load staff sessions
	        $sessions = session::retrieve_session_matching('ownerID', $uinfo['uname']);

	        // If no sessions loaded, use an empty array
	        if($sessions === false)
	            $sessions = array();

	        // Merge my sessions with those which I have access to
	        $sessions = array_merge($sessions, session::teacherExtraSessions($uinfo['uname']));

	        // Add My Sessions (Staff) section to page
		    $template_old->pageData['mainBody'] .= '<h2 class="page-section">My sessions (staff)</h2>';

		    // If user has no sessions, tell them that
		    if(sizeof($sessions) == 0)
		        $template_old->pageData['mainBody'] .= "<p>No sessions found</p>";

		    // Otherwise, list the sessions
		    else
		    {
		        $template_old->pageData['mainBody'] .= '<ul class="session-list">';
		        foreach($sessions as $s)
		        {
		            $ctime = strftime("%A %e %B %Y at %H:%M", $s->created);
		            $template_old->pageData['mainBody'] .= "<li><p class='session-title'><a href='runsession.php?sessionID={$s->id}'>{$s->title}</a><span class='user-badge session-id'><i class='fa fa-hashtag'></i> {$s->id}</span></p><p class='session-details'> Created $ctime</p><span class='feature-links'><a href='editsession.php?sessionID={$s->id}'><i class='fa fa-pencil'></i> Edit</a> <a href='confirmdelete.php?sessionID={$s->id}'><i class='fa fa-trash-o'></i> Delete</a></span></li>";
		        }
		        $template_old->pageData['mainBody'] .= '</ul>';
		    }
	    }

	    // Load user sessions
		$slist = sessionMember::retrieve_sessionMember_matching('userID', $uinfo['uname']);

        // Add My Sessions section to page
	    $template_old->pageData['mainBody'] .= '<h2 class="page-section">My sessions</h2>';

	    $sessions = array();

        // If slist array is set
	    if($slist)
	    {

	        // Loop for every session in slist
	        foreach($slist as $s)
	        {

	            // Get session from slist item
	            $sess = session::retrieve_session($s->session_id);

	            // If session exists and it is visisble, add it to the array of sessions to show
	            if(($sess)&&($sess->visible))
	                $sessions[] = $sess;
	        }
	    }

	    // If no sessions are available, tell the user
	    if(sizeof($sessions) == 0)
	        $template_old->pageData['mainBody'] .= "<p>No sessions found</p>";

	    // Otherwise output a list of sessions
	    else
	    {
	        $template_old->pageData['mainBody'] .= '<ul>';
	        foreach($sessions as $s)
	        {
	            $ctime = strftime("%A %e %B %Y at %H:%M", $s->created);
	            $template_old->pageData['mainBody'] .= "<li><a href='vote.php?sessionID={$s->id}'>{$s->title}</a>";
                if((isset($s->extras['allowFullReview']))&&($s->extras['allowFullReview']))
                     $template_old->pageData['mainBody'] .= " (<a href='review.php?sessionID={$s->id}'>Review previous answers</a>)";
                $template_old->pageData['mainBody'] .= "</li>";
	        }
	        $template_old->pageData['mainBody'] .= '</ul>';
	    }

	    // Load user info
        $user = userInfo::retrieve_by_username($uinfo['uname']);

	    // If user info loaded
        if($user !== false)
        {

            // Start a "My Settings" section of the page
	        $template_old->pageData['mainBody'] .= '<h2 class="page-section">My settings</h2>';

	        // If sms is setup
            if((isset($CFG['smsnumber']))&&(strlen($CFG['smsnumber'])))
            {

                // Add SMS details if so
	            $code = substr(md5($CFG['cookiehash'].$user->username),0,4);
	            if(strlen($user->phone))
	            {
	                $template_old->pageData['mainBody'] .= "<p>Current phone for SMS: {$user->phone}</p>";
	            }
	            $template_old->pageData['mainBody'] .= "<p>To associate a phone with your username text \"link {$user->username} $code\" (without quotes) to {$CFG['smsnumber']}.</p>";
            }
        }

        // If the user is an admin, display admin button
	    if($uinfo['isAdmin'])
	        $template_old->pageData['mainBody'] .= '<a href="admin.php" class="btn btn-danger"><i class="fa fa-wrench"></i> YACRS administration</a>';

        // Link to log user out
		$template_old->pageData['logoutLink'] = loginBox($uinfo);
    }
}

// Render old templating system
echo $template_old->render();

?>
