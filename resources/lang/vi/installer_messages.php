<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'title' => 'Beta app Installer',
    'next' => 'Bước tiếp',
    'back' => 'Trở lại',
    'finish' => 'Cài đặt',
    'forms' => [
        'errorTitle' => 'Đã xảy ra các lỗi sau:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'Xin chào',
        'title'   => 'Beta App Installer',
        'message' => 'Easy Installation and Setup Wizard.',
        'next'    => 'Kiểm tra các yêu cầu hệ thống',
    ],

    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Bước 1 | Yêu cầu hệ thống',
        'title' => 'Yêu cầu hệ thống',
        'next'    => 'Kiểm tra quyền',
    ],

    /**
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'Bước 2 | Kiểm tra quyền',
        'title' => 'Quyền truy xuất',
        'next' => 'Cấu hình môi trường',
    ],

    /**
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Bước 3 | Cài đặt môi trường',
            'title' => 'Cài đặt môi trường',
            'desc' => 'Bạn muốn sửa file <code>.env</code> như nào?',
            'wizard-button' => 'Cài đặt với biểu mẫu',
            'classic-button' => 'Chỉnh sửa file văn bản',
        ],
        'wizard' => [
            'templateTitle' => 'Bước 3 | Cài đặt môi trường | Thuật sĩ hướng dẫn',
            'title' => 'Hướng dẫn cài đặt <code>.env</code>',
            'tabs' => [
                'environment' => 'Môi trường',
                'database' => 'CSDL',
                'application' => 'App'
            ],
            'form' => [
                'name_required' => 'An environment name is required.',
                'app_name_label' => 'App Name',
                'app_name_placeholder' => 'App Name',
                'app_environment_label' => 'App Environment',
                'app_environment_label_local' => 'Local',
                'app_environment_label_developement' => 'Development',
                'app_environment_label_qa' => 'Qa',
                'app_environment_label_production' => 'Production',
                'app_environment_label_other' => 'Other',
                'app_environment_placeholder_other' => 'Enter your environment...',
                'app_debug_label' => 'App Debug',
                'app_debug_label_true' => 'True',
                'app_debug_label_false' => 'False',
                'app_log_level_label' => 'App Log Level',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'notice',
                'app_log_level_label_warning' => 'warning',
                'app_log_level_label_error' => 'error',
                'app_log_level_label_critical' => 'critical',
                'app_log_level_label_alert' => 'alert',
                'app_log_level_label_emergency' => 'emergency',
                'app_url_label' => 'App Url',
                'app_url_placeholder' => 'App Url',
                'db_connection_label' => 'Database Connection',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Database Host',
                'db_host_placeholder' => 'Database Host',
                'db_port_label' => 'Database Port',
                'db_port_placeholder' => 'Database Port',
                'db_name_label' => 'Database Name',
                'db_name_placeholder' => 'Database Name',
                'db_username_label' => 'Database User Name',
                'db_username_placeholder' => 'Database User Name',
                'db_password_label' => 'Database Password',
                'db_password_placeholder' => 'Database Password',

                'app_tabs' => [
                    'more_info' => 'More Info',
                    'broadcasting_title' => 'Broadcasting, Caching, Session, &amp; Queue',
                    'broadcasting_label' => 'Broadcast Driver',
                    'broadcasting_placeholder' => 'Broadcast Driver',
                    'cache_label' => 'Cache Driver',
                    'cache_placeholder' => 'Cache Driver',
                    'session_label' => 'Session Driver',
                    'session_placeholder' => 'Session Driver',
                    'queue_label' => 'Queue Connection',
                    'queue_placeholder' => 'Queue Connection',
                    'redis_label' => 'Redis Driver',
                    'redis_host' => 'Redis Host',
                    'redis_password' => 'Redis Password',
                    'redis_port' => 'Redis Port',

                    'mail_label' => 'Mail',
                    'mail_driver_label' => 'Trình điều khiển Mail',
                    'mail_driver_placeholder' => 'Trình điều khiển Mail',
                    'mail_host_label' => 'Máy chủ Mail',
                    'mail_host_placeholder' => 'Máy chủ Mail',
                    'mail_port_label' => 'Cổng',
                    'mail_port_placeholder' => 'Cổng',
                    'mail_username_label' => 'Tài khoản Mail',
                    'mail_username_placeholder' => 'Tài khoản Mail',
                    'mail_password_label' => 'Mật khẩu xác thực',
                    'mail_password_placeholder' => 'Mật khẩu xác thực',
                    'mail_encryption_label' => 'Kiểu mã hoá Mail',
                    'mail_encryption_placeholder' => 'Kiểu mã hoá Mail',

                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'Pusher App Id',
                    'pusher_app_id_palceholder' => 'Pusher App Id',
                    'pusher_app_key_label' => 'Pusher App Key',
                    'pusher_app_key_palceholder' => 'Pusher App Key',
                    'pusher_app_secret_label' => 'Pusher App Secret',
                    'pusher_app_secret_palceholder' => 'Pusher App Secret',
                ],
                'buttons' => [
                    'setup_database' => 'Cài đặt CSDL',
                    'setup_application' => 'Cài đặt App',
                    'install' => 'Cài đặt',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Bước 3 | Cài đặt môi trường | Trình chỉnh sửa',
            'title' => 'Trình chỉnh sửa môi trường',
            'save' => 'Lưu .env',
            'back' => 'Dùng thuật sĩ',
            'install' => 'Lưu cài đặt',
        ],
        'success' => 'Đã lưu tệp .env.',
        'errors' => 'Không thể lưu .env. Hãy tạo bằng tay.',
    ],

    'install' => 'Cài đặt',

    /**
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Laravel Installer successfully INSTALLED on ',
    ],

    /**
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Cài đặt hoàn tất',
        'templateTitle' => 'Cài đặt hoàn tất',
        'finished' => 'App đã cài đặt hoàn tất.',
        'migration' => 'Migration &amp; Seed Console Output:',
        'console' => 'Application Console Output:',
        'log' => 'Installation Log Entry:',
        'env' => 'Final .env File:',
        'exit' => 'Nhấn vào đây để thoát',
    ],

    /**
     *
     * Update specific translations
     *
     */
    'updater' => [
        /**
         *
         * Shared translations.
         *
         */
        'title' => 'Laravel Updater',

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Welcome To The Updater',
            'message' => 'Welcome to the update wizard.',
        ],

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Overview',
            'message' => 'There is 1 update.|There are :number updates.',
            'install_updates' => "Install Updates"
        ],

        /**
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Finished',
            'finished' => 'Application\'s database has been successfully updated.',
            'exit' => 'Click here to exit',
        ],

        'log' => [
            'success_message' => 'Laravel Installer successfully UPDATED on ',
        ],
    ],
];
