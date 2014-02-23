<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'jxgtaxo',
    'title'        => 'jxGTaxo - Google Product Taxonomy',
    'description'  => array(
                        'de'=>'Definition der Google Produkt Taxonomie je Kategorie',
                        'en'=>'Define the Google Product Taxonomy for each Category'
                        ),
    'thumbnail'    => 'jxgtaxo.png',
    'version'      => '0.1',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxGTaxo',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                        ),
    'files'        => array(
        'jxgtaxo'     => 'jxmods/jxgtaxo/application/controllers/admin/jxgtaxo.php'
                        ),
    'templates'    => array(
        'jxgtaxo.tpl' => 'jxmods/jxgtaxo/views/admin/tpl/jxgtaxo.tpl'
                        ),
    'events'       => array(
        'onActivate'   => 'jxgtaxo_install::onActivate', 
        'onDeactivate' => 'jxgtaxo_install::onDeactivate'
                        ),
    'settings'     => array(
                        array(
                            'group' => 'JXGTAXO_INCLUDEMODULES', 
                            'name'  => 'sJxGTaxoIncludeFiles', 
                            'type'  => 'str', 
                            'value' => ''
                            )
                        )
    );

?>
