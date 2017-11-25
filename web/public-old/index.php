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

// Set page breadcrumbs
$data["breadcrumbs"]->addItem("YACRS");

// If user is not logged in, render the login page
if($uinfo==false)
    die($templates->render("login", $data));

// Otherwise, user is logged in
else
{
    // If a session is specified, forward the user to the relevant page
    $thisSession = requestSet('sessionID') ? session::retrieve_session(requestInt('sessionID')):false;
    if($thisSession)
    {
        if(checkPermission($uinfo, $thisSession))
        {
            header("Location: runsession.php?sessionID={$thisSession->id}");
            die();
        }
        elseif(($thisSession->currentQuestion==0)&&($thisSession->ublogRoom>0))
        {
            header("Location: chat.php?sessionID={$thisSession->id}");
            die();
        }
        else
        {
            header("Location: vote.php?sessionID={$thisSession->id}");
            die();
        }
    }
    elseif($ltiSessionID = getLTISessionID())
    {
        if(isLTIStaff())
	    {
            $s = session::retrieve_session($ltiSessionID);
            $data["s"] = $s;
            die($templates->render("home_lti", $data));
	    }

	    // Otherwise, forward the user to the vote page for the session
        else
        {
            header("Location: vote.php?sessionID={$thisSession->id}");
            die();
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

        $user = userInfo::retrieve_by_username($uinfo['uname']);

        $data["staffSessions"] = $staffSessions;
        $data["sessions"] = $sessions;
        $data["uinfo"] = $uinfo;
        $data["user"] = $user;

        die($templates->render("home", $data));
    }
}
