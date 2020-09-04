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

    /**
     * @return string[]
     */
    protected static function allVisibleRoleObject()
    {
        return [
            'user', 'booking', 'cinema', 'combo', 'contact', 'customer', 'config', 'film', 'log', 'role', 'seat', 'show', 'slider', 'staticpage', 'schedule'
        ];
    }

    public static function mappedAttributeLabel()
    {
        return [
            'role_name' => __("Role name"),
            'dashboard' => __("Dashboard"),
            'user-view' => __()
        ];
    }

    public static function renderLogHtml($inputLogArray, $log = null)
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
            if ($item["action"] == "removed") {
                $rawHtml .= '<li>';
                $rawHtml .= __($item['action']) . '&nbsp;';
                $rawHtml .= "<d-mark-delete class='strikethrough'>";
                $rawHtml .= self::mappedAttributeLabel()[self::ROLE_NAME] ?? self::ROLE_NAME;
                $rawHtml .= "</d-mark-update>";
                $rawHtml .= '</li>';
            } else {
                foreach ($item['new_value'] as $_key => $_item) {
                    if ($_key == self::ROLE_NAME) {
                        $rawHtml .= '<li>';
                        $rawHtml .= __($item['action']) . '&nbsp;';
                        $rawHtml .= "<d-mark-update>";
                        $rawHtml .= self::mappedAttributeLabel()[self::ROLE_NAME] ?? self::ROLE_NAME;
                        $rawHtml .= "</d-mark-update>";
                        $newDataLog = __(
                            "&nbsp;from&nbsp;<d-mark-delete class='strikethrough'>:oldVal</d-mark-delete>&nbsp;to&nbsp;<d-mark-create>:newVal</d-mark-create>",
                            [
                                'oldVal' => $_item['old_value'],
                                'newVal' => $_item['new_value']
                            ]
                        );
                        $rawHtml .= $newDataLog;
                        $rawHtml .= '</li>';
                    }
                    if ($_key == self::PERMISSIONS) {
                        foreach ($_item['new_value'] as $permission) {
                            if (is_array($permission["new_value"])) {
                                $permissionCode = $permission['new_value']['permission_code']['new_value'];
                                $oldPermissionCode = $permission['new_value']['permission_code']['old_value'];
                            } else {
                                $permissionCode = $permission['new_value'];
                            }
                            if (!in_array($permissionCode, self::allVisibleRoleObject())) {
                                if (isset($oldPermissionCode) && $oldPermissionCode) {
                                    $rawHtml .= "<li>";
                                    $rawHtml .= __(
                                        "Removed :permission permission.",
                                        [
                                            "permission" => "<d-mark-delete class='strikethrough'>" . self::getLogRoleActionText($oldPermissionCode) . "</d-mark-delete>"
                                        ]
                                    ) . "</li>";
                                }
                                $permissionText = self::getLogRoleActionText($permissionCode);
                                $rawHtml .= "<li>";
                                if ($permission['action'] == 'updated') {
                                    $rawHtml .= __(
                                        "Add :permission permission.",
                                        [
                                            "permission" => "<d-mark-create>" . $permissionText . "</d-mark-create>"
                                        ]
                                    ) . "</li>";
                                }
                            }
                        }
                    }
                }
            }
        }
        return $rawHtml;
    }

    public static function getLogRoleActionText($permissionCode)
    {
        $pmsArr = explode("-", $permissionCode);
        if (isset($pmsArr[1])) {
            $permissionText = __($pmsArr[1]) . "&nbsp;" . __($pmsArr[0]);
        } else {
            $permissionText = __($pmsArr[0]);
        }
        return $permissionText;
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

    public function getId()
    {
        return $this->getAttribute('id');
    }

    public function getRoleName()
    {
        return $this->getAttribute('role_name');
    }
}
