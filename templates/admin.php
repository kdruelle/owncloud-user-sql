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

?>

<div class="section">
        <h2>SQL Backend</h2>
        <h3>Connection Settings</h3>
        <div class="sql-user-backend-field">
            <label for="user-sql-backend-db-driver">Driver :</label>
            <select id="user-sql-backend-db-driver">
                <option value="mysql" <?php if($_['db_driver'] === 'mysql'){p('selected');} ?>>MySQL</option>
            </select>
        </div>
        <div class="sql-user-backend-field">
            <label for="user-sql-backend-db-hostname">Hostname :</label>
            <input id="user-sql-backend-db-hostname" type="text" value="<?php p($_['db_hostname']) ?>"/>
        </div>
        <div class="sql-user-backend-field">
            <label for="user-sql-backend-db-username">Username :</label>
            <input id="user-sql-backend-db-username" type="text" value="<?php p($_['db_username']) ?>"/>
        </div>
        <div class="sql-user-backend-field">
            <label for="user-sql-backend-db-password">Password :</label>
            <input id="user-sql-backend-db-password" type="password" value="<?php p($_['db_password']) ?>"/>
        </div>
        <div class="sql-user-backend-field">
            <label for="user-sql-backend-db-name">Database Name :</label>
            <input id="user-sql-backend-db-name" type="text" value="<?php p($_['db_name']) ?>"/>
        </div>
        <div class="sql-user-backend-field">
            <label for="user-sql-backend-db-table">Table Name :</label>
            <input id="user-sql-backend-db-table" type="text" value="<?php p($_['db_table']) ?>"/>
        </div>
        <div class="sql-user-backend-field">
            <label for="user-sql-backend-db-ucolumn">Username Column :</label>
            <input id="user-sql-backend-db-ucolumn" type="text" value="<?php p($_['db_ucolumn']) ?>"/>
        </div>
        <div class="sql-user-backend-field">
            <label for="user-sql-backend-db-pcolumn">Password Column :</label>
            <input id="user-sql-backend-db-pcolumn" type="text" value="<?php p($_['db_pcolumn']) ?>"/>
        </div>
        <div class="sql-user-backend-field">
            <label for="user-sql-backend-db-cryptm">Password Colomn :</label>
            <select id="user-sql-backend-db-cryptm">
                <option value="md5crypt" <?php if($_['db_cryptm'] === 'md5crypt'){p('selected');} ?>>Crypt-MD5</option>
            </select>
        </div>
        
        <div>
            <p><strong id="sql-user-backend-error-field"></strong></p>
        </div>
        
        <input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']); ?>" id="requesttoken" />
        <input id="sql-user-backend-config-submit" type="submit" value="Save" disabled="1"/>
        
</div>


