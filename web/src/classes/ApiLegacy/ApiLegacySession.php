<?php

class ApiLegacySession
{

    /**
     * @param User $user
     * @param array $config
     * @param mysqli $mysqli
     */
    public static function sessionList($user, $config, $mysqli) {
        $errors = [];
        $data = [];

        // Load sessions from database
        $sessions = DatabaseSession::loadUserSessions($user->getId(), $mysqli);

        // Output each session
        foreach($sessions as $session) {
            $sessionInfo["ownerID"] = $session->getOwner();;
            $sessionInfo["title"] = $session->getTitle();
            $sessionInfo["created"] = $session->getCreated();
            $sessionInfo["attributes"]["id"] = $session->getSessionID();
            $data["sessionInfo"][] = $sessionInfo;
        }

        ApiLegacy::sendResponse("sessionlist", $errors, $data, $config);
    }

    /**
     * @param User $user
     * @param array $config
     * @param mysqli $mysqli
     */
    public static function sessionDetail($user, $config, $mysqli) {
        $errors = [];
        $data = [];

        // Load session from the database
        $session = DatabaseSession::loadSession($_REQUEST["id"], $mysqli);

        $data["sessionDetail"]["attributes"]["id"]      = $session->getSessionID();;
        $data["sessionDetail"]["title"]                 = $session->getTitle();
        $data["sessionDetail"]["courseIdentifier"]      = $session->getCourseID();
        $data["sessionDetail"]["allowGuests"]           = $session->getAllowGuests();
        $data["sessionDetail"]["visible"]               = $session->getOnSessionList();
        $data["sessionDetail"]["questionMode"]          = $session->getQuestionControlMode();
        $data["sessionDetail"]["defaultQuActiveSecs"]   = $session->getDefaultTimeLimit();
        $data["sessionDetail"]["allowQuReview"]         = $session->getAllowModifyAnswer();
        $data["sessionDetail"]["ublogRoom"]             = $session->getClassDiscussionEnabled();
        $data["sessionDetail"]["maxMessagelength"]      = -999;


        ApiLegacy::sendResponse("sessiondetail", $errors, $data, $config);
    }
}