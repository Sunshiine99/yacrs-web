<?php

class LoginTypeLdap implements LoginType
{

    private static function getCfg() {
        // LDAP server IP
        $CFG['ldaphost'] = '130.209.13.173';
        // LDAP context or list of contexts
        $CFG['ldapcontext'] = 'o=Gla';
        // LDAP Bind details
        #$CFG['ldapbinduser'] = '';
        #$CFG['ldapbindpass'] = '';
        // LDAP fields and values that result in sessionCreator (teacher) status
        $CFG['ldap_sessionCreator_rules'] = array();
        $CFG['ldap_sessionCreator_rules'][] = array('field'=>'dn', 'contains'=>'ou=staff');
        $CFG['ldap_sessionCreator_rules'][] = array('field'=>'homezipcode', 'match'=>'PGR');
        $CFG['ldap_sessionCreator_rules'][] = array('field'=>'uid', 'regex'=>'/^[a-z]{2,3}[0-9]+[a-z]$/');
        //$CFG['ldap_sessionCreator_rules'][] = array('field'=>'mail', 'regex'=>'/[a-zA-Z]+\.[a-zA-Z]+.*?@glasgow\.ac\.uk/');
        return $CFG;
    }

    /**
     * Checks login details. Returns userinfo array if success.
     * @param $username
     * @param $password
     * @return array|bool
     */
    public static function checkLogin($username, $password)
    {
        $CFG = self::getCfg();

        if(strlen(trim($password))==0)
            return false;
        //$error = false;
        $clrtime = time()+5; // For paranoid prevention of timing to narrow username/password guesses
        $cookiehash = $CFG['cookiehash'];
        $ldap_host = $CFG['ldaphost'];
        $ds = @ldap_connect($ldap_host);
        if(isset($CFG['ldapbinduser'])) {
            ldap_bind($ds, $CFG['ldapbinduser'], $CFG['ldapbindpass']);
        }
        if(!$ds)
        {
            //echo 'failed to contact LDAP server';
            return false;
        }
        $sr = @ldap_search($ds, $CFG['ldapcontext'], 'cn='.$username);
        if(!$sr)
        {
            //echo 'failed to contact LDAP server';
            return false;
        }
        $entry = ldap_first_entry($ds, $sr);
        if($entry)
        {
            $user_dn = ldap_get_dn($ds, $entry);
            $ok = @ldap_bind( $ds, $user_dn, $password);
            //ldap_free_result( $sr );
            if($ok)
            {
                $sr = ldap_search($ds, $CFG['ldapcontext'], 'cn='.$username);
                $count = ldap_count_entries( $ds, $sr );
                if($count>0)
                {
                    $records = ldap_get_entries($ds, $sr );
                    $record = $records[0];
                    return self::userFromLDAP($record);
                }
                else
                    //echo "No Identity vault entry found.<br/>";
                ldap_free_result( $sr );
            }
            else
            {
                while($clrtime < time()) sleep(1); // Paranoid prevention of timing to narrow username/password guesses
                //echo 'Incorrect password';
                return false; //Incorrect password
            }
        }
        else
        {
            while($clrtime < time()) sleep(1); // Paranoid prevention of timing to narrow username/password guesses
            //echo 'Incorrect username';
            return false; //Incorrect username
        }
    }

    private static function userFromLDAP($record) {
        $CFG = self::getCfg();

        $user = new User();
        $user->setUsername($record['uid'][0]);
        $user->setGivenName($record['givenname'][0]);
        $user->setSurname($record['sn'][0]);

        if(isset($record['mail'][0]))
            $user->setEmail($record['mail'][0]);
        elseif(isset($record['emailaddress'][0]))
            $user->setEmail($record['emailaddress'][0]);

        if(is_array($CFG['ldap_sessionCreator_rules'])) {

            foreach($CFG['ldap_sessionCreator_rules'] as $rule) {

                if(isset($record[$rule['field']])) {

                    is_array($record[$rule['field']]) ? $values = $record[$rule['field']] : $values = array($record[$rule['field']]);
                    foreach($values as $value) {

                        if((isset($rule['match']))&&($rule['match']==$value)) {
                            $user->setIsSessionCreator(true);
                        }

                        if((isset($rule['regex']))&&(preg_match($rule['regex'],$value))) {
                            $user->setIsSessionCreator(true);
                        }

                        if((isset($rule['contains']))&&(strpos($value, $rule['contains'])!==false)) {
                            $user->setIsSessionCreator(true);
                        }
                    }
                }
            }
        }

        // TODO: REMOVE
        $user->setIsSessionCreator(true);
        return $user;
    }
}
