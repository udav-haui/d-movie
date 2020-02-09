<script>
    let
    view = '{{ __('View') }}',
    create = '{{ __('Create') }}',
    edit = '{{ __('Edit') }}',
    deleteText = '{{ __('Delete') }}',
    roleText = '{{ __('Roles manage') }}',
    userText = '{{ __('User manage') }}',
    sliderText = '{{ __('Silder manage') }}',
    filmText = '{{ __('Films manage') }}';
    window.checkedNodes = '{{ old('permissions', $permissionsString ?? null) }}'.length === 0 ? [] :
        '{{ old('permissions', $permissionsString ?? null) }}'.split(',');
    window.permissionData = [{
        id: 0, text: '{{ __('Dmovie System') }}', expanded: true, spriteCssClass: "dmovie", items: [
            {
                id: 'user', text: userText, expanded: true, spriteCssClass: "user-item", items: [
                    { id: 'user-view', text: view, spriteCssClass: 'view' },
                    { id: 'user-create', text: create, spriteCssClass: "create" },
                    { id: 'user-edit', text: edit, spriteCssClass: "edit" },
                    { id: 'user-delete', text: deleteText, spriteCssClass: "delete" }
                ]
            },
            {
                id: 'role', text: roleText, expanded: false, spriteCssClass: "role-item", enabled: false, items: [
                    { id: 'role-view', text: view,  spriteCssClass: "view", enabled: false, checked: false  },
                    { id: 'role-create', text: create, enabled: false, spriteCssClass: "create" },
                    { id: 'role-edit', text: edit, enabled: false, spriteCssClass: "edit" },
                    { id: 'role-delete', text: deleteText, enabled: false, spriteCssClass: "delete" }
                ]
            },
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
        ]
    }]
</script>
