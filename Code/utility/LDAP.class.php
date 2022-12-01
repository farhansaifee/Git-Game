<?php
class LDAP
{
    private $ldap_con;
    private $ldap_bind;

    function connect($ldap_dn)
    {
        // Connecting to LDAP
        $this->$ldap_con = ldap_connect($ldap_dn);
        if($this->$ldap_con){
            ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
            return 0;
        }else{
            return 1;
        }

    }

    function bind($ldap_dn, $ldap_password){
        if ($this->$ldap_con) {
            // binding to ldap server
            $this->$ldap_bind = ldap_bind($this->$ldap_con, $ldap_dn, $ldap_password);
        
            // verify binding
            if ($this->$ldap_bind) {
                return 0;
            } else {
                return -1;
            }
        
        }
    }

    function getUserAttributes(){
        echo "testing";
    }

    function disconnect()
    {  // you will need this function later to close connection
        $this->ldap_con = NULL;
        return $this->conn;
    }
}
