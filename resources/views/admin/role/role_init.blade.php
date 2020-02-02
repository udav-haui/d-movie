<script>
    window.view = '{{ __('View') }}';
    window.create = '{{ __('Create') }}';
    window.edit = '{{ __('Edit') }}';
    window.deleteText = '{{ __('Delete') }}';
    window.roleText = '{{ __('Roles manage') }}';
    window.userText = '{{ __('User manage') }}';
    let sliderText = '{{ __('Silder manage') }}'
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
        ]
    }]
</script>
