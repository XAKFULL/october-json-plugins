<?php namespace XAKFULL\JsonPluginManager;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'JSON Plugin Manager',
            'description' => 'Управление плагинами через JSON',
            'author' => 'XAKFULL',
            'icon' => 'icon-archive'
        ];
    }

    public function registerNavigation()
    {
        return [
            'jsonpluginmanager' => [
                'label' => 'JSON Plugin Manager',
                'url' => \Backend::url('xakfull/jsonpluginmanager/importcontroller'),
                'icon' => 'icon-archive',
                'permissions' => ['xakfull.jsonpluginmanager.*'],
                'sideMenu' => [
                    'import' => [
                        'label' => 'Импорт',
                        'icon' => 'icon-download',
                        'url' => \Backend::url('xakfull/jsonpluginmanager/importcontroller')
                    ],
                    'export' => [
                        'label' => 'Экспорт',
                        'icon' => 'icon-upload',
                        'url' => \Backend::url('xakfull/jsonpluginmanager/exportcontroller')
                    ]
                ]
            ]
        ];
    }
}
