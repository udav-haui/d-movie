<?php

namespace App;

class Role extends AbstractModel
{
    protected $guarded = [];

    protected $with = ['users', 'permissions'];

    const ROLE_NAME = 'role_name';
    const PERMISSIONS = 'permissions';

    /**
     * @inheritDoc
     */
    public static function getModelName($option = null)
    {
        return __("Role");
    }

    public static function renderLogHtml($inputLogArray, $log)
    {
        $rawHtml = '<ul>';
        foreach ($inputLogArray as $key => $item) {
            $rawHtml .= '<li>';
            $targetModelLogText = __(
                "<code>:modelName</code>with identifier<code>:id</code>",
                [
                    'modelName' => \App\Role::getModelName(),
                    'id' => $log->getTargetId()
                ]
            );
            $rawHtml .= __(mb_strtoupper($item['action'])) . $targetModelLogText;
            $rawHtml .= "<ul>";
            foreach ($item['new_value'] as $_key => $_item) {

                if ($_key == self::ROLE_NAME) {
                    $rawHtml .= '<li>';
                    $rawHtml .= __($item['action']) . '&nbsp;';
                    $rawHtml .= "<d-mark-update>";
                    $rawHtml .= self::mappedAttributeLabel()[self::ROLE_NAME] ?? self::ROLE_NAME;
                    $rawHtml .= "</d-mark-update>";
                    $newDataLog = __(
                        "&nbsp;from&nbsp;<d-mark-delete>:oldVal</d-mark-delete>&nbsp;to&nbsp;<d-mark-create>:newVal</d-mark-create>",
                        [
                            'oldVal' => $_item['old_value'],
                            'newVal' => $_item['new_value']
                        ]
                    );
                    $rawHtml .= $newDataLog;
                    $rawHtml .= '</li>';
                }
                if ($_key == self::PERMISSIONS) {
                    foreach ($_item[self::PERMISSIONS]['new_value'] as $permission) {
                        $rawHtml .= "<li>";
                        $rawHtml .= __($permission['action']) . "&nbsp;";
                        if ($permission['action'] == 'updated') {
                            $rawHtml .= "<d-mark-create>";
                            $rawHtml .= $permission['new_value']['permission_code'];
                            $rawHtml .= "</d-mark-create>";
                        }
                    }
                }
            }
        }
        return $rawHtml;
    }

    /**
     * Define role permission
     */
    const ROLE_VIEW = 'role-view';
    const ROLE_CREATE = 'role-create';
    const ROLE_EDIT = 'role-edit';
    const ROLE_DELETE = 'role-delete';

    /**
     * A role has manny user use it
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * A role has many permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
