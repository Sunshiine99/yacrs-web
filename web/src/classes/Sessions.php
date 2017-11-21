<?php

class Sessions
{

    /**
     * Get the list of sessions that a user can control
     * @param $username
     * @param bool $legacyMode Whether to remain compatible with older versions of YACRS
     * @return array
     */
    public static function getUserOwnedSessions($username, $legacyMode=false) {

        // Load sessions owned primarily by this user
        $sessions = DatabaseSession::retrieveSessionMatching("ownerID", $username);

        // If the user owns no sessions, create an empty array
        if($sessions === false)
            $sessions = array();

        // Add any sessions that the user is an extra techer of
        $sessions = array_merge($sessions, DatabaseSession::teacherExtraSessions($username));

        // Add list of sessions to the output
        $output = [];
        if($sessions !== false) {
            foreach($sessions as $s) {
                $ctime = strftime("%Y-%m-%d %H:%M", $s->created);

                if($legacyMode)
                    $output[] = array('attributes'=>array('id'=>$s->id),'ownerID'=>$s->ownerID, 'title'=>$s->title, 'created'=>$ctime);

                else
                    $output[] = array('id'=>$s->id, 'ownerID'=>$s->ownerID, 'title'=>$s->title, 'created'=>$ctime);
            }
        }

        return $output;
    }

    public static function getSessionDetails($id) {
        if(isset($_REQUEST['id']))
        {
            $session = session::retrieve_session($_REQUEST['id']);
            if($session == false)
                $errors[] = "Session {$_REQUEST['id']} not found.";
            elseif(!checkPermission($uinfo, $session))
                $errors[] = "You do not have permission to modify session {$_REQUEST['id']}.";
        }
        else
        {
            $session = new session();
            $session->ownerID = $uinfo['uname'];
        }
        if(sizeof($errors) == 0)
        {
            $altered = false;
            if((isset($_REQUEST['title']))&&($_REQUEST['title'] != $session->title))
            {
                $session->title = $_REQUEST['title'];
                $altered = true;
            }
            if((isset($_REQUEST['allowGuests']))&&($_REQUEST['allowGuests'] != $session->allowGuests))
            {
                $session->allowGuests = $_REQUEST['allowGuests']==1;
                $altered = true;
            }
            if((isset($_REQUEST['visible']))&&($_REQUEST['visible'] != $session->visible))
            {
                $session->visible = $_REQUEST['visible']==1;
                $altered = true;
            }
            if((isset($_REQUEST['questionMode']))&&($_REQUEST['questionMode'] != $session->questionMode))
            {
                $session->questionMode = $_REQUEST['questionMode'];
                $altered = true;
            }
            if((isset($_REQUEST['defaultQuActiveSecs']))&&($_REQUEST['defaultQuActiveSecs'] != $session->defaultQuActiveSecs))
            {
                $session->defaultQuActiveSecs = $_REQUEST['defaultQuActiveSecs'];
                $altered = true;
            }
            if((isset($_REQUEST['allowQuReview']))&&($_REQUEST['allowQuReview'] != $session->allowQuReview))
            {
                $session->allowQuReview = $_REQUEST['allowQuReview'];
                $altered = true;
            }
            if((isset($_REQUEST['ublogRoom']))&&($_REQUEST['ublogRoom'] != $session->ublogRoom))
            {
                $session->ublogRoom = $_REQUEST['ublogRoom'];
                $altered = true;
            }
            if((isset($_REQUEST['maxMessagelength']))&&($_REQUEST['maxMessagelength'] != $session->maxMessagelength))
            {
                $session->maxMessagelength = $_REQUEST['maxMessagelength'];
                $altered = true;
            }
            if($session->id > 0)
                $session->update();
            else
                $session->insert();

            if((isset($_REQUEST['courseIdentifier']))&&($_REQUEST['courseIdentifier'] != $session->courseIdentifier))
            {
                $session->courseIdentifier = $_REQUEST['courseIdentifier'];
                if(strlen($session->courseIdentifier))
                    enrolStudents($session->id, $session->courseIdentifier);
                $altered = true;
            }
            $data['sessionDetail']['attributes'] = array('id'=>$session->id);
            $data['sessionDetail']['title'] = $session->title;
            $data['sessionDetail']['courseIdentifier'] = $session->courseIdentifier;
            $data['sessionDetail']['allowGuests'] = $session->allowGuests;
            $data['sessionDetail']['visible'] = $session->visible;
            $data['sessionDetail']['questionMode'] = $session->questionMode;
            $data['sessionDetail']['defaultQuActiveSecs'] = $session->defaultQuActiveSecs;
            $data['sessionDetail']['allowQuReview'] = $session->allowQuReview;
            $data['sessionDetail']['ublogRoom'] = $session->ublogRoom;
            $data['sessionDetail']['maxMessagelength'] = $session->maxMessagelength;
        }
    }
}