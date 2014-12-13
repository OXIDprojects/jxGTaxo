<?php
/*
 *    This file is part of the module jxGTaxo for OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      https://github.com/job963/jxGTaxo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2014
 * 
 */

class jxgtaxo_install
{ 
    public static function onActivate() 
    { 

        //$myConfig = oxRegistry::get("oxConfig");
        //$bConfig_DropOnDeactivate = $myConfig->getConfigParam("bJxInventoryDropOnDeactivate");
        $oDb = oxDb::getDb(); 

        $sSql = "ALTER TABLE oxcategories "
                . "ADD COLUMN `JXGOOGLETAXONOMY` VARCHAR(255) NULL "; 
                
        $oRs = $oDb->execute($sSql); 
        
        if ($oDb->errorNo() != 0) {
            echo '<div style="font-family:Arial;font-size:14px;border: 1px solid #dd0000;background-color:#ffdddd;margin:8px;padding:2px;">';
            echo '<span style="color:#000000;"><b>MySQL-Error ' . $oDb->errorNo() . ':</b><br />';
            echo $oDb->errorMsg() . '</span>';
            if (!empty($sSql)) {
                echo '<pre style="white-space:pre-wrap; color:#000000;">';
                echo $sSql;
                echo '</pre>';
            }
            echo '</div>';
            return FALSE;
        }
        
        return TRUE; 
    } 

    
    public static function onDeactivate() 
    { 
        // do nothing
        return TRUE; 
    }  
}

?>