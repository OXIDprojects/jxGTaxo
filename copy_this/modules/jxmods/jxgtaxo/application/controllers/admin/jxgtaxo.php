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
        $myConfig = oxRegistry::get("oxConfig");
        $sLogsDir = $myConfig->getLogsDir();
        $sShopUrl = $myConfig->getShopUrl();
        $oModule = oxNew('oxModule');
        $sModuleUrl = $sShopUrl . 'modules/' . $oModule->getModulePath("jxcmdboard") . '/';
        
        /*$aIncFiles = array();
        $aIncFiles = explode( ',', $myConfig->getConfigParam("sJxCmdBoardIncludeFiles") );
        $aIncModules = array();
        $sIncPath = $this->jxGetModulePath() . '/application/controllers/admin/';
        foreach ($aIncFiles as $sIncFile) { 
            $sIncFile = $sIncPath . 'jxcmd_' . $sIncFile . '.inc.php';
            require $sIncFile;
        } 

        $oSmarty->assign("aIncModules",$aIncModules);
        $oSmarty->assign("output",$this->output);
        $oSmarty->assign("response",$this->response['http_code']);
        $oSmarty->assign("exectime",$this->exectime);
        $oSmarty->assign("exectitle",oxConfig::getParameter("jxcmd_title"));*/
        
        $this->jxGetCategoryList('oxrootid', '');
        $this->jxSortCategoryList();
        /*echo '<pre>';
        print_r($this->aCategories);
        echo '</pre>';*/
        
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
            //echo '<pre>'.$sSql.'</pre><br />';
            $oDb->execute($sSql);
        }
        return;
    }
    
    
    public function jxGetCategoryList( $sParent, $sPath )
    {
        if ( !empty($sPath) )
            $sPath .= '.';
        
        $sSql = "SELECT c.oxid, c.oxtitle, (SELECT COUNT(*) FROM oxcategories c1 WHERE c1.oxparentid=c.oxid) AS count, c.jxgoogletaxonomy AS taxonomy "
                . "FROM oxcategories c "
                . "WHERE c.oxparentid = '$sParent' "
                    . "AND c.oxactive = 1 "
                . "ORDER BY c.oxtitle";
        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        $rs = $oDb->Execute($sSql);
        //$aDelDefs = array();
        $i = 1;
        while (!$rs->EOF) {
            $aCols = $rs->fields;
            $aCols['path'] = $sPath . $i;
            array_push($this->aCategories, $aCols);
            if ($aCols['count'] != 0)
                $this->jxGetCategoryList($aCols['oxid'], $aCols['path']);
            $rs->MoveNext();
            $i++;
        }

            
    }
    
    
    public function jxSortCategoryList()
    {
        $aSort = array();
        foreach ($this->aCategories as $key => $aRow) {
            $aSort[$key] = $aRow['path'];
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