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

// check if user is admin
OCP\User::checkAdminUser();

// add javascript file
OCP\Util::addStyle('user_sql_backend', 'admin');
OCP\Util::addScript('user_sql_backend', 'admin');

$tpl = new OCP\Template('user_sql_backend', 'admin');

$tpl->assign('db_driver',   OCP\Config::getAppValue('user_sql_backend', 'db_driver',   OC_USER_SQL_BACKEND_DEFAULT_DRIVER));
$tpl->assign('db_hostname', OCP\Config::getAppValue('user_sql_backend', 'db_hostname', OC_USER_SQL_BACKEND_DEFAULT_HOSTNAME));
$tpl->assign('db_username', OCP\Config::getAppValue('user_sql_backend', 'db_username', OC_USER_SQL_BACKEND_DEFAULT_USERNAME));
$tpl->assign('db_password', OCP\Config::getAppValue('user_sql_backend', 'db_password', OC_USER_SQL_BACKEND_DEFAULT_PASSWORD));
$tpl->assign('db_name',     OCP\Config::getAppValue('user_sql_backend', 'db_name',     OC_USER_SQL_BACKEND_DEFAULT_DBNAME));
$tpl->assign('db_table',    OCP\Config::getAppValue('user_sql_backend', 'db_table',    OC_USER_SQL_BACKEND_DEFAULT_TABLENAME));
$tpl->assign('db_ucolumn',  OCP\Config::getAppValue('user_sql_backend', 'db_ucolumn',  OC_USER_SQL_BACKEND_DEFAULT_USER_COLUMN));
$tpl->assign('db_pcolumn',  OCP\Config::getAppValue('user_sql_backend', 'db_pcolumn',  OC_USER_SQL_BACKEND_DEFAULT_PASSWORD_COLUMN));
$tpl->assign('db_cryptm',   OCP\Config::getAppValue('user_sql_backend', 'db_cryptm',   OC_USER_SQL_BACKEND_DEFAULT_CRYPT_METHOD));

return $tpl->fetchPage();
