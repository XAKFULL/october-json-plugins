<?php namespace XAKFULL\JsonPluginManager\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Http\Request;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class ImportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('XAKFULL.JsonPluginManager', 'jsonpluginmanager');
    }

    public function index()
    {
        $this->pageTitle = 'JSON Plugin Manager';
    }

    public function onImport(Request $request)
    {
        $json = $request->input('json');

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Если JSON некорректен, возвращаем ошибку
            \Flash::error('Некорректный JSON');
            return;
        }

        foreach ($data as $path => $code) {

            $path = str_replace('plugins/', '', $path);

            $fullPath = plugins_path($path);

            // Создание директорий, если они не существуют
            $dir = dirname($fullPath);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            // Запись кода в файл
            file_put_contents($fullPath, $code);
        }

        // Возвращаем успешный ответ
        \Flash::success('Импорт завершен!');
        return;
    }
}
