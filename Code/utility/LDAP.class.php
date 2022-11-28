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
            echo "LDAP object is created</br>";
        }else{
            echo "LDAP object is empty </br>";
        }

    }

    function bind($ldap_dn, $ldap_password){
        if ($this->$ldap_con) {
            // binding to ldap server
            $temp = ldap_bind($this->$ldap_con, $ldap_dn, $ldap_password);
        
            // verify binding
            if ($temp) {
                echo "LDAP bind successful...</br>";
            } else {
                echo "LDAP bind failed...</br>";
                echo "ldap_error: " . ldap_error($this->$ldap_con) . "</br>";
            }
        
        }
    }

    function disconnect()
    {  // you will need this function later to close connection
        $this->ldap_con = NULL;
        return $this->conn;
    }
}
