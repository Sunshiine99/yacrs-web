<?php

class ApiSessions
{

    public static function listSessions() {

        // Required parameters
        $key = Api::checkParameter("key");

        // If invalid api key, output error
        if(!Api::checkApiKey($key))
            ApiError::invalidApiKey();

        $sessions = DatabaseSession::retrieveSessionMatching("ownerID", "admin");

        if($sessions === false)
            $sessions = array();
        $sessions = array_merge($sessions, DatabaseSession::teacherExtraSessions("teacher"));
        $data['sessionInfo'] = array();
        if($sessions !== false)
        {
            foreach($sessions as $s)
            {
                $ctime = strftime("%Y-%m-%d %H:%M", $s->getCreated());
                $data['sessionInfo'][] = array('attributes'=>array('id'=>$s->getId()),'ownerID'=>$s->getOwnerID(), 'title'=>$s->getTitle(), 'created'=>$ctime);
            }
        }

        $output["sessions"] = [];

        foreach($sessions as $session) {
            array_push($output["sessions"], $session->toArray());
        }

        Api::output($output);
    }
}