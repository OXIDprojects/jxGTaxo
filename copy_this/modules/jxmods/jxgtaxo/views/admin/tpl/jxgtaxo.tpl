[{*debug*}]
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]
[{*<link href="[{$oViewConf->getModuleUrl('jxgtaxo','out/admin/src/jxcmdboard.css')}]" type="text/css" rel="stylesheet">*}]

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

<style>
    .flatInput {
        padding-left: 4px;
        border: 1px solid transparent;
        width: 99%;
        background-color: transparent;
    }
    .flatInput:hover {
        border: 1px solid #a0a0a0;
        background-color: #ffffff;
    }
    .flatInput:focus {
        background-color: #ffffff;
        box-shadow: 0 0 3px #0000ff;
    }
</style>

[{php}] 
    $sIsoLang = oxLang::getInstance()->getLanguageAbbr(); 
    $this->assign('IsoLang', $sIsoLang);
[{/php}]

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
                            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_CATEGORY" }]</div></div></td>
                            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="JXGTAXO_TAXOEDITHERE" }]</div></div></td>
                        </tr>
                        
                [{foreach name=rows item=category from=$aCategories}]
                    [{ cycle values="listitem,listitem2" assign="listclass" }]
                    <tr>
                        <td class="[{$listclass}]">
                            <div class="listitemfloating">&nbsp;[{ $category.path }]&nbsp;</div>
                            <input type="hidden" name="jxgt_catid[]" value="[{$category.oxid}]">
                        </td>
                        <td class="[{$listclass}]">&nbsp;[{ $category.oxtitle }]&nbsp;</td>
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

