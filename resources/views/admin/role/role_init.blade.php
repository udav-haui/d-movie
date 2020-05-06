<script>
    let
    view = '{{ __('View') }}',
    create = '{{ __('Create') }}',
    edit = '{{ __('Edit') }}',
        dashboardText = '{{ __('Dashboard') }}'
    deleteText = '{{ __('Delete') }}',
    roleText = '{{ __('Roles manage') }}',
    userText = '{{ __('User manage') }}',
    sliderText = '{{ __('Silder manage') }}',
    filmText = '{{ __('Films manage') }}',
    cinemaText = '{{ __('Cinemas manage') }}',
    showText = '{{ __('Shows manage') }}',
        seatText = '{{ __('Seat manage') }}',
        scheduleText = '{{ __('Schedule manage') }}',
        contactText = '{{ __('Contacts manage') }}',
        customerText = '{{ __('Customers manage') }}',
        staticPageText = '{{ __('Static pages manage') }}',
        logText = '{{ __('System logs') }}',
        bookingText = '{{ __('Sales Bookings') }}',
        printTicketText = '{{ __('Print Ticket') }}',
        comboText = '{{ __('Combos manage') }}';
    window.checkedNodes = '{{ old('permissions', $permissionsString ?? null) }}'.length === 0 ? [] :
        '{{ old('permissions', $permissionsString ?? null) }}'.split(',');
    window.permissionData = [{
        id: 0, text: '{{ __('Dmovie System') }}', expanded: true, spriteCssClass: "dmovie", items: [
            { id: 'dashboard', text: dashboardText, expanded: true, spriteCssClass: "dashboard-item" },
            {
                id: 'user', text: userText, expanded: true, spriteCssClass: "user-item", items: [
                    { id: 'user-view', text: view, spriteCssClass: 'view' },
                    { id: 'user-create', text: create, spriteCssClass: "create" },
                    { id: 'user-edit', text: edit, spriteCssClass: "edit" },
                    { id: 'user-delete', text: deleteText, spriteCssClass: "delete" }
                ]
            },
            {
                id: 'booking', text: bookingText, expanded: true, spriteCssClass: "booking-item", items: [
                    { id: 'booking-view', text: view, spriteCssClass: 'view' },
                    { id: 'booking-create', text: create, spriteCssClass: "create" },
                    { id: 'booking-edit', text: edit, spriteCssClass: "edit" },
                    { id: 'booking-delete', text: deleteText, spriteCssClass: "delete" },
                    { id: 'print-ticket', text: printTicketText, spriteCssClass: "print-ticket"}
                ]
            },
            {
                id: 'customer', text: customerText, expanded: true, spriteCssClass: "customer-item", items: [
                    { id: 'customer-view', text: view, spriteCssClass: 'view' },
                    { id: 'customer-create', text: create, spriteCssClass: "create" },
                    { id: 'customer-edit', text: edit, spriteCssClass: "edit" },
                    { id: 'customer-delete', text: deleteText, spriteCssClass: "delete" }
                ]
            },
            // {
            //     id: 'role', text: roleText, expanded: false, spriteCssClass: "role-item", enabled: false, items: [
            //         { id: 'role-view', text: view,  spriteCssClass: "view", enabled: false, checked: false  },
            //         { id: 'role-create', text: create, enabled: false, spriteCssClass: "create" },
            //         { id: 'role-edit', text: edit, enabled: false, spriteCssClass: "edit" },
            //         { id: 'role-delete', text: deleteText, enabled: false, spriteCssClass: "delete" }
            //     ]
            // },
            {
                id: 'slider', text: sliderText, expanded: true, spriteCssClass: "slider-item", items: [
                    { id: 'slider-view', text: view,  spriteCssClass: "view" },
                    { id: 'slider-create', text: create, spriteCssClass: "create" },
                    { id: 'slider-edit', text: edit, spriteCssClass: "edit" },
                    { id: 'slider-delete', text: deleteText, spriteCssClass: "delete" }
                ]
            },
            {
                id: 'film', text: filmText, expanded: true, spriteCssClass: "film-item", items: [
                    { id: 'film-view', text: view,  spriteCssClass: "view" },
                    { id: 'film-create', text: create, spriteCssClass: "create" },
                    { id: 'film-edit', text: edit, spriteCssClass: "edit" },
                    { id: 'film-delete', text: deleteText, spriteCssClass: "delete" }
                ]
            },
            {
                id: 'combo', text: comboText, expanded: true, spriteCssClass: "combo-item", items: [
                    { id: 'combo-view', text: view,  spriteCssClass: "view" },
                    { id: 'combo-create', text: create, spriteCssClass: "create" },
                    { id: 'combo-edit', text: edit, spriteCssClass: "edit" },
                    { id: 'combo-delete', text: deleteText, spriteCssClass: "delete" }
                ]
            },
            {
                id: 'cinema', text: cinemaText, expanded: true, spriteCssClass: "cinema-item", items: [
                    { id: 'cinema-view', text: view,  spriteCssClass: "view" },
                    { id: 'cinema-create', text: create, spriteCssClass: "create" },
                    { id: 'cinema-edit', text: edit, spriteCssClass: "edit" },
                    { id: 'cinema-delete', text: deleteText, spriteCssClass: "delete" },
                    {
                        id: 'show', text: showText, expanded: true, spriteCssClass: "show-item", items: [
                            { id: 'show-view', text: view,  spriteCssClass: "view" },
                            { id: 'show-create', text: create, spriteCssClass: "create" },
                            { id: 'show-edit', text: edit, spriteCssClass: "edit" },
                            { id: 'show-delete', text: deleteText, spriteCssClass: "delete" },
                            {
                                id: 'booking', text: seatText, expanded: true, spriteCssClass: "booking-item", items: [
                                    { id: 'booking-view', text: view,  spriteCssClass: "view" },
                                    { id: 'booking-create', text: create, spriteCssClass: "create" },
                                    { id: 'booking-edit', text: edit, spriteCssClass: "edit" },
                                    { id: 'booking-delete', text: deleteText, spriteCssClass: "delete" }
                                ]
                            },
                            {
                                id: 'schedule', text: scheduleText, expanded: true, spriteCssClass: "schedule-item", items: [
                                    { id: 'schedule-view', text: view,  spriteCssClass: "view" },
                                    { id: 'schedule-create', text: create, spriteCssClass: "create" },
                                    { id: 'schedule-edit', text: edit, spriteCssClass: "edit" },
                                    { id: 'schedule-delete', text: deleteText, spriteCssClass: "delete" }
                                ]
                            }
                        ]
                    },
                    {
                        id: 'contact', text: contactText, expanded: true, spriteCssClass: "contact-item", items: [
                            { id: 'contact-view', text: view,  spriteCssClass: "view" },
                            { id: 'contact-create', text: create, spriteCssClass: "create" },
                            { id: 'contact-edit', text: edit, spriteCssClass: "edit" },
                            { id: 'contact-delete', text: deleteText, spriteCssClass: "delete" }
                        ]
                    },
                ]
            },
            {
                id: 'staticpage', text: staticPageText, expanded: true, spriteCssClass: "staticpage-item", items: [
                    { id: 'staticpage-view', text: view,  spriteCssClass: "view" },
                    { id: 'staticpage-create', text: create, spriteCssClass: "create" },
                    { id: 'staticpage-edit', text: edit, spriteCssClass: "edit" },
                    { id: 'staticpage-delete', text: deleteText, spriteCssClass: "delete" }
                ]
            },
            {
                id: 'log', text: logText, expanded: true, spriteCssClass: "log-item", items: [
                    { id: 'log-view', text: view,  spriteCssClass: "view" }
                ]
            },
        ]
    }]
</script>
