<?php namespace XAKFULL\JsonPluginManager\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Http\Request;
use XAKFULL\JsonPluginManager\Classes\PluginManager;

class ImportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('XAKFULL.JsonPluginManager', 'jsonpluginmanager', 'import');
    }

    public function index()
    {
        $this->pageTitle = 'JSON Plugin Manager';
    }

    public function onImport(Request $request)
    {
        $json = $request->input('json');

        $manager = new PluginManager();

        try {
            $manager->import($json);
        } catch (\Exception $e) {
            return response()->abort(500, $e->getMessage());
        }

        // Возвращаем успешный ответ
        \Flash::success('Импорт завершен!');
    }

}
