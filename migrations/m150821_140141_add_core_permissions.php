<?php

use yeesoft\db\PermissionsMigration;

class m150821_140141_add_core_permissions extends PermissionsMigration
{

    public function beforeUp()
    {
        $this->addRole(self::ROLE_USER, 'User');

        $this->addRole(self::ROLE_AUTHOR, 'Author');
        $this->addChild(self::ROLE_AUTHOR, self::ROLE_USER);

        $this->addRole(self::ROLE_MODERATOR, 'Moderator');
        $this->addChild(self::ROLE_MODERATOR, [self::ROLE_USER, self::ROLE_AUTHOR]);

        $this->addRole(self::ROLE_ADMIN, 'Administrator');
        $this->addChild(self::ROLE_ADMIN, [self::ROLE_USER, self::ROLE_AUTHOR, self::ROLE_MODERATOR]);

        $this->addPermissionsGroup('Dashboard', 'Dashboard');
        $this->addPermissionsGroup('UserCommonPermissions', 'Common Permissions');

        $this->addRule('AuthorRule', 'yeesoft\rbac\AuthorRule');

        $this->addModel('Page', 'yeesoft\page\models\Page');
        $this->addModel('Post', 'yeesoft\post\models\Post');

        $this->addFilter('AuthorFilter', 'yeesoft\filters\AuthorFilter');
        
        $this->addFilterToModel('AuthorFilter', ['Page', 'Post']);
        //add remove method
        
        $this->addFilterToRole('AuthorFilter', [self::ROLE_USER, self::ROLE_AUTHOR]);
        //add remove method
        
        
        //add permission
        //add child permission
        //add rule to permision
        //add route to permission
        //add route
    }

    public function afterDown()
    {
        $this->removeFilter('AuthorFilter');

        $this->removeModel('Post');
        $this->removeModel('Page');

        $this->removeRule('authorRule');

        $this->removePermissionsGroup('dashboard');
        $this->removePermissionsGroup('userCommonPermissions');

        $this->removeRole(self::ROLE_ADMIN);
        $this->removeRole(self::ROLE_MODERATOR);
        $this->removeRole(self::ROLE_AUTHOR);
        $this->removeRole(self::ROLE_USER);
    }

    public function getPermissions()
    {
        return [
            'dashboard' => [
                'links' => [
                    '/admin/*',
                    '/admin/default/*',
                ],
                'viewDashboard' => [
                    'title' => 'View Dashboard',
                    'roles' => [self::ROLE_AUTHOR],
                    'links' => [
                        '/admin',
                        '/admin/site/index',
                    ],
                ],
            ],
            'userCommonPermissions' => [
                'commonPermission' => [
                    'title' => 'Common Permission',
                    'roles' => [self::ROLE_USER],
                ],
                'changeOwnPassword' => [
                    'title' => 'Change Own Password',
                    'roles' => [self::ROLE_USER],
                ],
            ],
        ];
    }

}
