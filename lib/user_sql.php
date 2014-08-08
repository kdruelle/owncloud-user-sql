<?php

/**
 * ownCloud - user_sql_backend
 *
 * @author Kevin Druelle
 * @copyright 2012 Kevin Druelle <kevin@druelle.info>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


require dirname(__FILE__) . '/crypto.php';


class OC_USER_SQL extends OC_User_Backend implements OC_User_Interface {
    
    /**
     * @var mixed SQL Connection 
     */
    protected $db = null;
    
    /**
     *
     * @var bool  
     */
    protected $db_conn = false;
    
    /**
     *
     * @var String 
     */
    protected $db_table = '';
    
    /**
     *
     * @var String 
     */
    protected $db_ucolumn = '';
    
    /**
     *
     * @var String 
     */
    protected $db_cryptm = '';


    /**
     *
     * @var String 
     */
    protected $db_pcolumn = '';
    
    /**
     * Contructor
     * @throws PDOException
     */
    public function __construct() {
        $db_driver = OCP\Config::getAppValue('user_sql_backend', 'db_driver', '');
        $db_hostname = OCP\Config::getAppValue('user_sql_backend', 'db_hostname', '');
        $db_username = OCP\Config::getAppValue('user_sql_backend', 'db_username', '');
        $db_name = OCP\Config::getAppValue('user_sql_backend', 'db_name', '');
        $db_password = OCP\Config::getAppValue('user_sql_backend', 'db_password', '');
        
        $dsn = "$db_driver:host=$db_hostname;dbname=$db_name";
        
        try {
            $this->db = new PDO($dsn, $db_username, $db_password);
            $this->db_conn = true;
        } catch (PDOException $e) {
            OC_Log::write('OC_USER_SQL', 'Failed to connect to the database: ' . $e->getMessage(), OC_Log::ERROR);
            throw $e;
        }
        
        $this->db_table   = OCP\Config::getAppValue('user_sql_backend', 'db_table', '');
        $this->db_ucolumn = OCP\Config::getAppValue('user_sql_backend', 'db_ucolumn', '');
        $this->db_pcolumn = OCP\Config::getAppValue('user_sql_backend', 'db_pcolumn', '');
        $this->db_cryptm  = OCP\Config::getAppValue('user_sql_backend', 'db_cryptm', '');
        
    }
    
    
    
    /**
     * check if a user exists
     * @param string $uid the username
     * @return boolean
     */
    public function userExists($uid){
        
        if(!$this->db_conn){
            return false;
        }
        
        $uid = strtolower(trim($uid));
        
        $query = "SELECT $this->db_ucolumn, $this->db_pcolumn FROM $this->db_table WHERE $this->db_ucolumn = :uid";
        $params = array(
            ':uid' => $uid
        );                

        $result = $this->db->prepare($query);
        
        if(!$result->execute($params)){
            $err = $result->errorInfo();
            OC_Log::write('OC_USER_SQL', "Error executing query: ".$err[2], OC_Log::ERROR);
            return false;
        }

        if($result->fetch()){
            return true;
        }
        OC_Log::write('OC_USER_SQL', "User $uid does not exists", OC_Log::ERROR);
        return false;
    }
    
    
    
    /**
     * Get a list of all users
     * @return array an array of all uids
     *
     * Get a list of all users.
     */
    public function getUsers($search = '', $limit = null, $offset = null){
        $users = array();
        
        if (!$this->db_conn) {
            return false;
        }
        $query = "SELECT $this->db_ucolumn FROM $this->db_table";
        
        if($search != ''){
            $query .= " WHERE $this->db_ucolumn LIKE :search";
        }

        $query .= " ORDER BY $this->db_ucolumn";
        
        if($limit != null){
            $limit = intval($limit);
            $query .= " LIMIT $limit";
        }
        
        if($offset != null){
            $offset = intval($offset);
            $query .= " OFFSET $offset";
        }
       
        $result = $this->db->prepare($query);
        if($search != ''){
            $search = "%$search%";
            $result->bindParam(":search", $search);
        }
       
        if(!$result->execute(array(':search', $search))){
            $err = $result->errorInfo();
            OC_Log::write('OC_USER_SQL', "Error executing query: ".$err[2], OC_Log::ERROR);
            return array();
        }
       
        while($row = $result->fetch()){
            $uid = $row[$this->db_ucolumn];
            $users[] = strtolower($uid);
        }
        return $users;
    }
   
    
    /**
    * @brief Check if the password is correct
    * @param $uid The username
    * @param $password The password
    * @returns true/false
    *
    * Check if the password is correct without logging in the user
    */
    public function checkPassword($uid, $password)
    {

        if(!$this->db_conn){
            return false;
        }
        
        $uid = strtolower(trim($uid));

        $query = "SELECT $this->db_ucolumn, $this->db_pcolumn FROM $this->db_table WHERE $this->db_ucolumn = :uid";
        $params = array(
            ':uid' => $uid
        );
        
        $result = $this->db->prepare($query);
        
        if(!$result->execute($params)){
            $err = $result->errorInfo();
            OC_Log::write('OC_USER_SQL', "Error executing query: ".$err[2], OC_Log::ERROR);
            return false;
        }
        
        $row = $result->fetch();
        if(!$row)
        {
            OC_Log::write('OC_USER_SQL', "CheckPassword failed, no user returned", OC_Log::ERROR);
            return false;
        }
        
        $crypto = new Crypto($this->db_cryptm);
        if($crypto->encrypt_password($password, $row[$this->db_pcolumn]) == $row[$this->db_pcolumn])
        {
            return $uid;
        }
        else
        {
            OC_Log::write('OC_USER_SQL', "Passwords verification failed '$this->db_cryptm'", OC_Log::ERROR);
            return false;
        }
    }
    
}


