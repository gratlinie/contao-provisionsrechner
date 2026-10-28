<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_module']['palettes']['provisionsrechner'] = '{title_legend},name,headline,type;{pr_config_legend},prName,prOrders,prAverage,prRate,prYears;{pr_offers_legend},prOffers;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['fields'] += [
    'prName' => [
        'inputType' => 'text',
        'eval' => ['mandatory' => true, 'maxlength' => 64, 'tl_class' => 'w50'],
        'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
    ],
    'prOrders' => [
        'inputType' => 'text',
        'eval' => ['rgxp' => 'natural', 'minval' => 10, 'maxval' => 400, 'tl_class' => 'w25 clr'],
        'sql' => ['type' => 'smallint', 'unsigned' => true, 'default' => 60],
    ],
    'prAverage' => [
        'inputType' => 'text',
        'eval' => ['rgxp' => 'natural', 'minval' => 10, 'maxval' => 80, 'tl_class' => 'w25'],
        'sql' => ['type' => 'smallint', 'unsigned' => true, 'default' => 25],
    ],
    'prRate' => [
        'inputType' => 'text',
        'eval' => ['rgxp' => 'digit', 'minval' => 5, 'maxval' => 35, 'tl_class' => 'w25'],
        'sql' => ['type' => 'decimal', 'precision' => 4, 'scale' => 1, 'default' => '14.0'],
    ],
    'prYears' => [
        'inputType' => 'text',
        'eval' => ['rgxp' => 'natural', 'minval' => 2, 'maxval' => 10, 'tl_class' => 'w25'],
        'sql' => ['type' => 'smallint', 'unsigned' => true, 'default' => 3],
    ],
    'prOffers' => [
        'inputType' => 'rowWizard',
        'fields' => [
            'title' => [
                'label' => &$GLOBALS['TL_LANG']['tl_module']['prOfferTitle'],
                'inputType' => 'text',
                'eval' => ['basicEntities' => true],
            ],
            'yearly' => [
                'label' => &$GLOBALS['TL_LANG']['tl_module']['prOfferYearly'],
                'inputType' => 'text',
                'eval' => ['rgxp' => 'digit'],
            ],
            'once' => [
                'label' => &$GLOBALS['TL_LANG']['tl_module']['prOfferOnce'],
                'inputType' => 'text',
                'eval' => ['rgxp' => 'digit'],
            ],
        ],
        'eval' => ['tl_class' => 'clr'],
        'sql' => ['type' => 'text', 'notnull' => false],
    ],
];
