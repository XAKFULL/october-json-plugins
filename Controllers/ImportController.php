<?php namespace XAKFULL\JsonPluginManager\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Http\Request;

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

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Если JSON некорректен, возвращаем ошибку
            \Flash::error('Некорректный JSON');
            return;
        }

        foreach ($data as $item) {

            $fullPath = plugins_path($item['path']);

            // Создание директорий, если они не существуют
            $dir = dirname($fullPath);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            if (isset($item['content']) and $item['type'] == 'file') {
                // Загрузите файл из base64 decode
                $content = base64_decode($item['content'], true);
                file_put_contents($fullPath, $content);
            }
        }

        // Возвращаем успешный ответ
        \Flash::success('Импорт завершен!');
        return;
    }

}
