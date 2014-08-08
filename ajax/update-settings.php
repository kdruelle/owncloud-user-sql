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


// Check if user is admin
OCP\User::checkAdminUser();

// Set headers for JSON response
header('Content-Type: application/json');

$response = array('status' => 'OK', 'code' => 0);

if(!isset($_POST) || !isset($_POST['json'])){
    $response['status'] = 'KO';
    $response['code'] = 1;
    $response['error'][] = array('post' => 'not set');
    echo json_encode($response);
    exit(0);
}

$json = json_decode($_POST['json'], true);

extract($json);

// list all required params
$params = array(
    'db_driver', 
    'db_hostname', 
    'db_username', 
    'db_password', 
    'db_name', 
    'db_table', 
    'db_ucolumn', 
    'db_pcolumn', 
    'db_cryptm');

foreach ($params as $param){
    if ($param === 'db_password'){
        if(!isset($$param)){
            $response['status'] = 'KO';
            $response['code'] = 1;
            $response['error'][] = array($param => 'not set');
        }
        continue;
    }
    if (!isset($$param) || !$$param) {
        $response['status'] = 'KO';
        $response['code'] = 1;
        $response['error'][] = array($param => 'not set');
        continue;
    }
    if ($param === 'db_driver') {
        switch ($db_driver) {
            case 'mysql':
                break;
            default:
                $response['status'] = 'KO';
                $response['code'] = 1;
                $response['error'][] = array($param => 'value ' . $db_driver . ' not acceptable');
                continue;
        }
    }
    if ($param === 'db_cryptm') {
        switch ($db_cryptm) {
            case 'md5crypt':
                break;
            default:
                $response['status'] = 'KO';
                $response['code'] = 1;
                $response['error'][] = array($param => 'value ' . $db_cryptm . ' not acceptable');
                continue;
        }
    }
}


try {
    $dsn = "$db_driver:host=$db_hostname;db_name=$db_name";
    $db = new PDO($dsn, $db_username, $db_password);
} catch (PDOException $e) {
    $response['status'] = 'KO';
    $response['code'] = 2;
    $response['error'][] = array('connection' => $e->getMessage());
}



if ($response['status'] === 'OK') {
    $response['code'] = 0;
    $response['message'] = 'saved succed';
    
    // Save recieved params in OC database
    foreach ($params as $param) {
        OCP\Config::setAppValue('user_sql_backend', $param, $$param);
    }
}

echo json_encode($response);

