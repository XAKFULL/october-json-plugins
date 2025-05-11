<?php namespace XAKFULL\JsonPluginManager\Controllers;

use Backend\Classes\Controller;
use XAKFULL\JsonPluginManager\Classes\PluginManager;

class ExportController extends Controller
{
    protected $pluginManager;

    public function __construct()
    {
        parent::__construct();
        \BackendMenu::setContext('XAKFULL.JsonPluginManager', 'jsonpluginmanager', 'export');

        $this->pluginManager = new PluginManager();
        $this->pluginManager->loadPlugins();
    }

    public function index(){}

    protected function listPlugins()
    {
        return $this->pluginManager->getPlugins();
    }

    public function onExport()
    {
        $pluginPath = input('plugin', '');

        if ($pluginPath != ''){

            $data = $this->pluginManager->export($pluginPath);
            $pluginInfo = $this->pluginManager->getPluginInfo($pluginPath);

            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            return response()->streamDownload(function () use ($json) {echo $json;}, $pluginInfo['name'].'.json');
        }

        return response()->abort('500', 'Plugin not found');
    }
}
