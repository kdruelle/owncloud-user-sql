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


// define DEFAULTS VALUES
define('OC_USER_SQL_BACKEND_DEFAULT_DRIVER',             'mysql');
define('OC_USER_SQL_BACKEND_DEFAULT_HOSTNAME',           'localhost');
define('OC_USER_SQL_BACKEND_DEFAULT_USERNAME',           'mail_admin');
define('OC_USER_SQL_BACKEND_DEFAULT_PASSWORD',           'password');
define('OC_USER_SQL_BACKEND_DEFAULT_DBNAME',             'postfix');
define('OC_USER_SQL_BACKEND_DEFAULT_TABLENAME',          'mailbox');
define('OC_USER_SQL_BACKEND_DEFAULT_USER_COLUMN',        'username');
define('OC_USER_SQL_BACKEND_DEFAULT_PASSWORD_COLUMN',    'password');
define('OC_USER_SQL_BACKEND_DEFAULT_CRYPT_METHOD',       'md5crypt');

require_once(dirname(__FILE__) . '/user_sql.php');

class USER_SQL_App {
    
    private static $_application = null;

    static public function create(){
        USER_SQL_App::$_application = new USER_SQL_App();
        USER_SQL_App::$_application->init();
    }

    private function __construct() {
        
    }

    public function init(){
        //register admin screen
        OC_App::registerAdmin('user_sql_backend','lib/admin');
        
        // register user backend
        OC_User::registerBackend('SQL');
        OC_User::useBackend('SQL');
        
    }
    
}

