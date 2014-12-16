[{*debug*}]
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]
<link href="[{$oViewConf->getModuleUrl('jxgtaxo','out/admin/src/jxgtaxo.css')}]" type="text/css" rel="stylesheet">

<script type="text/javascript">
  if(top)
  {
    top.sMenuItem    = "[{ oxmultilang ident="mxservice" }]";
    top.sMenuSubItem = "[{ oxmultilang ident="jxgtaxo_menu" }]";
    top.sWorkArea    = "[{$_act}]";
    top.setTitle();
  }

    function resizeCodeFrame () {
        var codeframe = document.getElementById('codeframe');
        codeframe.style.height = (window.innerHeight - 150) + "px";;
    }
</script>

[{php}] 
    $sIsoLang = oxLang::getInstance()->getLanguageAbbr(); 
    $this->assign('IsoLang', $sIsoLang);
[{/php}]

[{assign var="oConfig" value=$oViewConf->getConfig()}]

<body>
<div class="center" style="height:100%;">
    <h1>[{ oxmultilang ident="JXGTAXO_TITLE" }]</h1>
    <p>
        <form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
            [{ $shop->hiddensid }]
            <input type="hidden" name="oxid" value="[{ $oxid }]">
            <input type="hidden" name="cl" value="article" size="40">
            <input type="hidden" name="updatelist" value="1">
        </form>
        
        <div class="jxcmdboard">
            
            [{if $output}]
                <div id="popupwin" class="jxpopupwin">
                    <div style="[{if $response == "200"}]background:#008000;[{else}]background:#800000;[{/if}]color:#fff;padding:4px;">
                        <span style="padding-left:10px;font-size:1.2em;font-weight:bold;">[{$exectitle}]</span> 
                        <span style="padding-left:40px;">[{ oxmultilang ident="JXCMDBOARD_DURATION" }]: <b>[{$exectime}] sec.</b></span>
                        <span style="padding-left:40px;">[{ oxmultilang ident="JXCMDBOARD_RESPONSE" }]: <b>[{if $response == "200"}]OK[{else}]ERROR: [{$response}][{/if}]</b></span>
                    </div>
                    <div class="jxpopupclose" onclick="document.getElementById('popupwin').style.display='none';document.getElementById('grayout').style.display='none';">
                        <div style="height:3px;"></div>
                        <b>X</b></div>
                    <div class="jxpopupcontent">[{ $output }]</div>
                </div>
            [{/if}]
            
            <div id="grayout" class="jxgrayout" [{if $output}]style="display:block;"[{else}]style="display:none;"[{/if}]></div>


            <form name="jxgtaxo" id="jxcmd" action="[{ $oViewConf->getSelfLink() }]" method="post">
                [{ $oViewConf->getHiddenSid() }]
                <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
                <input type="hidden" name="fnc" value="">
                <input type="hidden" name="oxid" value="[{ $oxid }]">
                
                <input type="submit"
                    onClick="document.forms['jxgtaxo'].elements['fnc'].value = 'saveTaxoValues';" 
                    value=" [{ oxmultilang ident="GENERAL_SAVE" }] " [{ $readonly }]>
                <div>&nbsp;</div>

                <div id="liste">
                    <table cellspacing="0" cellpadding="0" border="0" width="99%">
                        <tr>
                            [{ assign var="headStyle" value="border-bottom:1px solid #C8C8C8; font-weight:bold;" }]
                            <td class="listfilter first" style="[{$headStyle}]" height="15" width="30" align="center">
                                <div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_ACTIVTITLE" }]</div></div>
                            </td>
                            [{ if $oConfig->getConfigParam("sJxGTaxoDisplayHidden") }]
                                <td class="listfilter" style="[{$headStyle}]" width="30" align="center"><div class="r1"><div class="b1">[{ oxmultilang ident="JXGTAXO_HIDDEN" }]</div></div></td>
                            [{/if}]
                            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1"> </div></div></td>
                            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_CATEGORY" }]</div></div></td>
                            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1"> </div></div></td>
                            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="JXGTAXO_TAXOEDITHERE" }]</div></div></td>
                        </tr>
                        
                [{foreach name=rows item=category from=$aCategories}]
                    [{ cycle values="listitem,listitem2" assign="listclass" }]
                    <tr>
                        <td valign="top" class="[{ $listclass}][{if $category.oxactive == 1}] active[{/if}]">
                            <div class="listitemfloating">
                                &nbsp;
                            </div>
                        </td>
                        [{ if $oConfig->getConfigParam("sJxGTaxoDisplayHidden") }]
                            <td valign="top" class="[{ $listclass}][{if $category.oxhidden == 1 }] hidden[{/if}]">
                                <div class="listitemfloating" align="center">
                                    &nbsp;
                                </div>
                            </td>
                        [{/if}]
                        <td class="[{$listclass}]">
                            <div class="listitemfloating">&nbsp;[{ $category.path }]&nbsp;</div>
                            <input type="hidden" name="jxgt_catid[]" value="[{$category.oxid}]">
                        </td>
                        <td class="[{$listclass}]">&nbsp;[{ $category.oxtitle }]&nbsp;</td>
                        <td class="[{$listclass}]" align="right">&nbsp;[{ $category.artcount }]&nbsp;</td>
                        <td class="[{$listclass}]"><input id="" name="jxgt_taxoval[]" size="120" value="[{ $category.taxonomy }]" class="flatInput"></td>
                    </tr>
                [{/foreach}]
                </table>
                </div>
                <input type="submit"
                    onClick="document.forms['jxgtaxo'].elements['fnc'].value = 'saveTaxoValues';" 
                    value=" [{ oxmultilang ident="GENERAL_SAVE" }] " [{ $readonly }]>
            </form>
        </div>
    </p>
    <div style="position:absolute; bottom:0px; left:0px; height:50px; background-color:#dd0000;"></div>

</div>

</body>

