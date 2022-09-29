<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sites
    |--------------------------------------------------------------------------
    |
    | You may configure a different currency & different shipping methods for each
    | of your 'multi-site' sites.
    |
    | https://simple-commerce.duncanmcclean.com/multi-site
    |
    */

    'sites' => [
        'default' => [
            'currency' => 'EUR',
            'shipping' => [
                'methods' => [
                    \DoubleThreeDigital\SimpleCommerce\Shipping\StandardPost::class => [],
                ],
            ],
        ],
        'english' => [
            'currency' => 'EUR',
            'shipping' => [
                'methods' => [
                    \DoubleThreeDigital\SimpleCommerce\Shipping\StandardPost::class => [],
                ],
            ],
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    |
    | This is where you configure the payment gateways you wish to use across
    | your site. You may configure as many as you like.
    |
    | https://simple-commerce.duncanmcclean.com/gateways
    |
    */

    'gateways' => [
      \DoubleThreeDigital\SimpleCommerce\Gateways\Builtin\MollieGateway::class => [
        'key' => env('MOLLIE_KEY'),
        'profile' => env('MOLLIE_PROFILE'),
      ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Simple Commerce is able to send notifications after certain 'events' happen,
    | like an order being marked as paid. You may configure these notifications
    | below.
    |
    | https://simple-commerce.duncanmcclean.com/notifications
    |
    */

    'notifications' => [
        'order_paid' => [
            \App\Notifications\CustomerOrderPaid::class => [
                'to' => 'customer',
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    |
    | Configure the Cart Driver in use on your site. It's what stores/gets the
    | Cart ID from the user's browser on every request.
    |
    */

    'cart' => [
        'repository' => \DoubleThreeDigital\SimpleCommerce\Orders\Cart\Drivers\CookieDriver::class,
        'key' => 'simple-commerce-cart',
        'single_cart' => true
     ],

    /*
    |--------------------------------------------------------------------------
    | Field Whitelist
    |--------------------------------------------------------------------------
    |
    | You may configure the fields you wish to be editable via front-end forms
    | below. Wildcards are not accepted due to security concerns.
    |
    | https://simple-commerce.duncanmcclean.com/tags#field-whitelisting
    |
    */

    'field_whitelist' => [
        'orders' => [
            'shipping_name', 'shipping_address', 'shipping_address_line2', 'shipping_city', 'shipping_region',
            'shipping_postal_code', 'shipping_country', 'use_shipping_address_for_billing', 'billing_name', 'billing_address',
            'billing_address_line2', 'billing_city', 'billing_region', 'billing_postal_code', 'billing_country',
            'club', 'best_time', 'gender', 'birthyear', 'shirt_size', 'competitors',
            'address', 'zip', 'city', 'company_name', 'company_id', 'company_address',
            'company_taxpayer', 'contact_name', 'contact_phone', 'footnote',
            'dp_vet', 'email_notifications', 'sms_notifications', 'terms_and_conditions', 'gdpr',
            'phone', 'country', 'dog_name', 'dog_breed', 'dog_birthyear', 'primary_school'
        ],

        'line_items' => [],

        'customers' => ['name', 'email'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Stock Running Low
    |--------------------------------------------------------------------------
    |
    | Simple Commerce will emit events when stock is running low for a product.
    | You may configure the threshold used to decide 'when' a product is
    | running low.
    |
    | https://simple-commerce.duncanmcclean.com/stock
    |
    */

    'low_stock_threshold' => 10,

    /*
    |--------------------------------------------------------------------------
    | Tax
    |--------------------------------------------------------------------------
    |
    | Configure the 'tax engine' you'd like to be used to calculate tax rates
    | throughout your site.
    |
    | https://simple-commerce.duncanmcclean.com/tax
    |
    */

    'tax_engine' => \DoubleThreeDigital\SimpleCommerce\Tax\Standard\TaxEngine::class,

    'tax_engine_config' => [
        // Basic Engine
        'rate'               => 22,
        'included_in_prices' => true,

        // Standard Tax Engine
        'address' => 'billing',

        'behaviour' => [
            'no_address_provided' => 'default_address',
            'no_rate_available' => 'prevent_checkout',
        ],

        'default_address' => [
            'address_line_1' => '',
            'address_line_2' => '',
            'city' => '',
            'region' => '',
            'country' => '',
            'zip_code' => '',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Drivers
    |--------------------------------------------------------------------------
    |
    | Normally, all products/orders/etc are stored as entries. However, as your
    | store grows you may which to use a database instead. This is where you
    | come to switch out the 'entry driver' for the 'database driver'.
    |
    | https://simple-commerce.duncanmcclean.com/extending/content-drivers
    |
    */

    'content' => [
        'coupons' => [
            'repository' => \DoubleThreeDigital\SimpleCommerce\Coupons\EntryCouponRepository::class,
            'collection' => 'coupons',
        ],

        'customers' => [
            'repository' => \DoubleThreeDigital\SimpleCommerce\Customers\EntryCustomerRepository::class,
            'collection' => 'customers',
        ],

        'orders' => [
            'repository' => \DoubleThreeDigital\SimpleCommerce\Orders\EntryOrderRepository::class,
            'collection' => 'orders',
        ],

        'products' => [
            'repository' => \DoubleThreeDigital\SimpleCommerce\Products\EntryProductRepository::class,
            'collection' => 'products',
        ],
    ],

];
