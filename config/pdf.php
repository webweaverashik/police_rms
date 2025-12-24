<?php

return [

    'mode'                    => 'utf-8',
    'format'                  => 'A4',

    'default_font_size'       => 12,

    // 🔑 MUST be SolaimanLipi
    'default_font'            => 'solaimanlipi',

    'margin_left'             => 15,
    'margin_right'            => 15,
    'margin_top'              => 15,
    'margin_bottom'           => 15,

    'orientation'             => 'P',

    // 🔑 VERY IMPORTANT FOR BANGLA
    'auto_language_detection' => true,

    // 🔑 FONT DIRECTORY
    'custom_font_dir'         => public_path('fonts'),

    // 🔑 FONT REGISTRATION WITH OTL
    'custom_font_data'        => [
        'solaimanlipi' => [
            'R'      => 'SolaimanLipi.ttf',
            'useOTL' => 0xFF, // 🔥 THIS FIXES যুক্তাক্ষর
        ],
    ],

    'temp_dir'                => storage_path('app'),

    // Leave these as-is
    'watermark'               => '',
    'show_watermark'          => false,
    'show_watermark_image'    => false,
    'display_mode'            => 'fullpage',
    'pdfa'                    => false,
    'pdfaauto'                => false,
    'use_active_forms'        => false,
];
