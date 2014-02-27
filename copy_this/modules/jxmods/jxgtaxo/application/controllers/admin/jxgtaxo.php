<?php

/*
 *    This file is part of the module jxGTaxo for OXID eShop Community Edition.
 *
 *    The module jxGTaxo for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module jxGTaxo for OXID eShop Community Edition is distributed in the hope that it will be useful,
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
 
class jxgtaxo extends oxAdminView
{
    protected $_sThisTemplate = "jxgtaxo.tpl";
    protected $aCategories = array();
            
    public function render()
    {
        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);
        
        $this->jxGetCategoryList('oxrootid', '', '');
        $this->jxSortCategoryList();
        
        $oSmarty->assign("aCategories",$this->aCategories);

        return $this->_sThisTemplate;
    }
    
    
    public function saveTaxoValues()
    {
        $oDb = oxDb::getDb();
        $aCatIds = oxConfig::getParameter( "jxgt_catid" ); 
        $aTaxoVals = oxConfig::getParameter( "jxgt_taxoval" ); 
        foreach ($aTaxoVals as $key => $sTaxoValue) {
            $sSql = "UPDATE oxcategories SET jxgoogletaxonomy = '{$aTaxoVals[$key]}' WHERE oxid = '{$aCatIds[$key]}' ";
            $oDb->execute($sSql);
        }
        return;
    }
    
    
    public function jxGetCategoryList( $sParent, $sNoPath, $sCatPath )
    {
        $myConfig = $this->getConfig();
        
        if ( !empty($sNoPath) ) {
            $sNoPath .= '.';
            $sCatPath .= ' / ';
        }
        
        $sWhere = "";
        if ( $myConfig->getConfigParam("sJxGTaxoDisplayInactive") == FALSE )
            $sWhere .= "AND c.oxactive = 1 ";
        if ( $myConfig->getConfigParam("sJxGTaxoDisplayHidden") == FALSE )
            $sWhere .= "AND c.oxhidden = 0 ";
        
        $sSql = "SELECT c.oxid, c.oxtitle, c.oxactive, c.oxhidden, "
                    . "(SELECT COUNT(*) FROM oxobject2category o2c WHERE o2c.oxcatnid = c.oxid) AS artcount, "
                    . "(SELECT COUNT(*) FROM oxcategories c1 WHERE c1.oxparentid=c.oxid) AS count, c.jxgoogletaxonomy AS taxonomy "
                . "FROM oxcategories c "
                . "WHERE c.oxparentid = '$sParent' "
                    . $sWhere
                . "ORDER BY c.oxtitle";
        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        $rs = $oDb->Execute($sSql);

        $i = 1;
        while (!$rs->EOF) {
            $aCols = $rs->fields;
            $aCols['path'] = $sNoPath . $i;
            $aCols['oxtitle'] = $sCatPath . $aCols['oxtitle'];
            array_push($this->aCategories, $aCols);
            if ($aCols['count'] != 0) {
                $this->jxGetCategoryList($aCols['oxid'], $aCols['path'], $aCols['oxtitle']);
            }
            $rs->MoveNext();
            $i++;
        }
    }
    
    
    public function jxSortCategoryList()
    {
        $aSort = array();
        foreach ($this->aCategories as $key => $aRow) {
            $aSort[$key] = $aRow['oxtitle'];
        }
        array_multisort($aSort, SORT_ASC, SORT_STRING, $this->aCategories);
    }
    
    
    public function execute()
    {
        $url = oxConfig::getParameter( "jxcmd_url" );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
        $timeStart = time();
        $output = curl_exec($ch).' ';
        $this->exectime = time() - $timeStart;
        $this->response = curl_getinfo( $ch );
        curl_close($ch);
        
        $this->output = $output;
        return;
    }

    
    public function jxGetModulePath()
    {
        $sModuleId = $this->getEditObjectId();

        $this->_aViewData['oxid'] = $sModuleId;

        $oModule = oxNew('oxModule');
        $oModule->load($sModuleId);
        $sModuleId = $oModule->getId();
        
        $myConfig = oxRegistry::get("oxConfig");
        $sModulePath = $myConfig->getConfigParam("sShopDir") . 'modules/' . $oModule->getModulePath("jxcmdboard");
        
        return $sModulePath;
    }
    
}
?>