<?php namespace XAKFULL\JsonPluginManager\Controllers;

use Backend\Classes\Controller;

class ExportController extends Controller
{

    public $plugins;

    public function __construct()
    {
        parent::__construct();
        \BackendMenu::setContext('XAKFULL.JsonPluginManager', 'jsonpluginmanager', 'export');
    }

    public function index(){
        $this->loadPlugins();
    }

    protected function loadPlugins()
    {
        $this->plugins = $this->scanPluginsDirectory();
    }

    public function onExport()
    {
        $pluginPath = input('plugin');

        $json = '';


        if ($pluginPath != ''){

            $dir = plugins_path($pluginPath);

            $pluginName = str_replace('/', '_', $pluginPath);

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {

                $path = $item->getPathname();
                $relativePath = str_replace(plugins_path().'/', '', $path);

                $entry = [
                    'name' => $item->getFilename(),
                    'path' => $relativePath,
                    'type' => $item->isDir() ? 'directory' : 'file'
                ];
                if (!$item->isDir()) {
                    $content = file_get_contents($item->getPathname());
                    $entry['content'] = base64_encode($content);
                    $entry['size'] = $item->getSize();
                    $data[] = $entry;
                }
            }

            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            return response()->streamDownload(function () use ($json) {echo $json;}, $pluginName.'.json');
        }

        return $json;
    }

    private function scanPluginsDirectory()
    {
        $plugins = [];
        $iterator = new \DirectoryIterator(plugins_path());

        foreach ($iterator as $authorsFolders) {

            if ($authorsFolders->isDir() && !$authorsFolders->isDot()) {

                $author = $authorsFolders->getFilename();

                foreach (new \DirectoryIterator($authorsFolders->getRealPath()) as $pluginFolder) {

                    if ($pluginFolder->isDir() && !$pluginFolder->isDot()) {

                        $plugin = $pluginFolder->getFilename();

                        $plugins[$author.'/'.$plugin] = $author.'.'.$plugin;
                    }
                }

            }
        }
        return $plugins;
    }
}
