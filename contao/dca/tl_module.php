<?php

declare(strict_types=1);

use Gratlinie\ProvisionsrechnerBundle\Controller\ProvisionsrechnerController;

$GLOBALS['TL_DCA']['tl_module']['palettes']['provisionsrechner'] = '{title_legend},name,headline,type;{pr_config_legend},prName,prCurrency,prYears;{pr_sliders_legend},prOrdersLabel,prOrders,prAverageLabel,prAverage,prRateLabel,prRate;{pr_offers_legend},prOffers;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

// Je Regler: Beschriftung und Start, Minimum, Maximum, Schritt
$slider = static fn (string $name): array => [
    $name.'Label' => [
        'inputType' => 'text',
        'eval' => ['maxlength' => 255, 'placeholder' => $GLOBALS['TL_LANG']['MSC']['provisionsrechner'][strtolower(substr($name, 2))] ?? '', 'tl_class' => 'w50 clr'],
        'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
    ],
    $name => [
        'inputType' => 'text',
        'default' => ProvisionsrechnerController::SLIDERS[strtolower(substr($name, 2))],
        'eval' => ['multiple' => true, 'size' => 4, 'rgxp' => 'digit', 'mandatory' => true, 'tl_class' => 'w50'],
        'sql' => ['type' => 'string', 'length' => 128, 'default' => ''],
    ],
];

$GLOBALS['TL_DCA']['tl_module']['fields'] += [
    'prName' => [
        'inputType' => 'text',
        'eval' => ['mandatory' => true, 'maxlength' => 64, 'tl_class' => 'w50'],
        'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
    ],
    'prCurrency' => [
        'inputType' => 'text',
        'default' => 'EUR',
        'eval' => ['mandatory' => true, 'maxlength' => 3, 'rgxp' => 'alpha', 'tl_class' => 'w25'],
        'sql' => ['type' => 'string', 'length' => 3, 'default' => 'EUR'],
    ],
    'prYears' => [
        'inputType' => 'text',
        'default' => 3,
        'eval' => ['mandatory' => true, 'rgxp' => 'natural', 'minval' => 1, 'maxval' => 20, 'tl_class' => 'w25'],
        'sql' => ['type' => 'smallint', 'unsigned' => true, 'default' => 3],
    ],
    ...$slider('prOrders'),
    ...$slider('prAverage'),
    ...$slider('prRate'),
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
