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


class Crypto{
    
    private $_crypto_type = '';


    public function __construct($crypto_type) {
        $this->_crypto_type = $crypto_type;
    }
    
    
    public function encrypt_password($clear_pwd, $db_pwd=""){
        
        if ($this->_crypto_type == 'md5crypt') {
            $split_salt = preg_split ('/\$/', $db_pwd);
            if (isset ($split_salt[2])) {
                $salt = $split_salt[2];
            }
            return $this->md5crypt ($clear_pwd, $salt);
        }
        
        return false;
    }  
    
    
    
    
    // Functions below are from postfixadmin
    
    
    private function md5crypt ($pw, $salt="", $magic="")
    {
        $MAGIC = "$1$";

        if ($magic == "") $magic = $MAGIC;
        if ($salt == "") $salt = $this->create_salt ();
        $slist = explode ("$", $salt);
        if ($slist[0] == "1") $salt = $slist[1];

        $salt = substr ($salt, 0, 8);
        $ctx = $pw . $magic . $salt;
        $final = $this->pahex2bin (md5 ($pw . $salt . $pw));

        for ($i=strlen ($pw); $i>0; $i-=16)
        {
            if ($i > 16)
            {
                $ctx .= substr ($final,0,16);
            }
            else
            {
                $ctx .= substr ($final,0,$i);
            }
        }
        $i = strlen ($pw);

        while ($i > 0)
        {
            if ($i & 1) $ctx .= chr (0);
            else $ctx .= $pw[0];
            $i = $i >> 1;
        }
        $final = $this->pahex2bin (md5 ($ctx));

        for ($i=0;$i<1000;$i++)
        {
            $ctx1 = "";
            if ($i & 1)
            {
                $ctx1 .= $pw;
            }
            else
            {
                $ctx1 .= substr ($final,0,16);
            }
            if ($i % 3) $ctx1 .= $salt;
            if ($i % 7) $ctx1 .= $pw;
            if ($i & 1)
            {
                $ctx1 .= substr ($final,0,16);
            }
            else
            {
                $ctx1 .= $pw;
            }
            $final = $this->pahex2bin (md5 ($ctx1));
        }
        $passwd = "";
        $passwd .= $this->to64 (((ord ($final[0]) << 16) | (ord ($final[6]) << 8) | (ord ($final[12]))), 4);
        $passwd .= $this->to64 (((ord ($final[1]) << 16) | (ord ($final[7]) << 8) | (ord ($final[13]))), 4);
        $passwd .= $this->to64 (((ord ($final[2]) << 16) | (ord ($final[8]) << 8) | (ord ($final[14]))), 4);
        $passwd .= $this->to64 (((ord ($final[3]) << 16) | (ord ($final[9]) << 8) | (ord ($final[15]))), 4);
        $passwd .= $this->to64 (((ord ($final[4]) << 16) | (ord ($final[10]) << 8) | (ord ($final[5]))), 4);
        $passwd .= $this->to64 (ord ($final[11]), 2);
        return "$magic$salt\$$passwd";
    }
    
    
    private function create_salt (){
        srand ((double) microtime ()*1000000);
        $salt = substr (md5 (rand (0,9999999)), 0, 8);
        return $salt;
    }
    
    
    private function pahex2bin ($str)
    {
        if(function_exists('hex2bin'))
        {
            return hex2bin($str);
        }
        else
        {
            $len = strlen ($str);
            $nstr = "";
            for ($i=0;$i<$len;$i+=2)
            {
                $num = sscanf (substr ($str,$i,2), "%x");
                $nstr.=chr ($num[0]);
            }
            return $nstr;
        }
    }
    
    private function to64 ($v, $n)
    {
        $ITOA64 = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $ret = "";
        while (($n - 1) >= 0)
        {
            $n--;
            $ret .= $ITOA64[$v & 0x3f];
            $v = $v >> 6;
        }
        return $ret;
    }
    
}







