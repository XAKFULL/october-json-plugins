<?php namespace XAKFULL\JsonPluginManager;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'JSON Plugin Manager',
            'description' => 'Импорт и экспорт плагинов через JSON',
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
                'permissions' => ['xakfull.importpluginfromjson.*'],
                'order' => 500
            ],
        ];
    }

    public function register()
    {
    }
}
