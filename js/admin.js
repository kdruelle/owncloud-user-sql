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

$(document).ready(function(){
    
    $('input', '.sql-user-backend-field').change(function(){
        $('#sql-user-backend-config-submit').removeAttr('disabled');
    });
    
    $('#sql-user-backend-config-submit').click(function(){
        $.ajax({
            type : 'POST',
            url : OC.generateUrl('apps/user_sql_backend/ajax/update-settings'),
            //dataType : 'json',
            //contentType : 'application/json',
            processData : false,
            data : 'json=' + JSON.stringify({
                db_driver   : $('#user-sql-backend-db-driver').val(),
                db_hostname : $('#user-sql-backend-db-hostname').val(),
                db_username : $('#user-sql-backend-db-username').val(),
                db_password : $('#user-sql-backend-db-password').val(),
                db_name     : $('#user-sql-backend-db-name').val(),
                db_table    : $('#user-sql-backend-db-table').val(),
                db_ucolumn  : $('#user-sql-backend-db-ucolumn').val(),
                db_pcolumn  : $('#user-sql-backend-db-pcolumn').val(),
                db_cryptm   : $('#user-sql-backend-db-cryptm').val()
            }),
            success : function(json){
                console.log(json);
                $('#sql-user-backend-error-field').removeClass();
                if(json.code === 0){
                    $('#sql-user-backend-config-submit').attr('disabled', 'disabled');
                    $('#sql-user-backend-error-field').addClass('success');
                    $('#sql-user-backend-error-field').html(json.message);
                }
                if(json.code === 2){
                    $('#sql-user-backend-error-field').addClass('error');
                    $('#sql-user-backend-error-field').html(json.message);
                }
            },
            error : function(xhr, status, errorThrown){
                alert( "Sorry, there was a problem!" );
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            }
        });
    });
    
});




