<?php namespace XAKFULL\JsonPluginManager\Classes;


class PluginManager
{

    private $plugins;

    const SKIP = [
        'vendor',
        '.git',
        '.gitignore',
        'composer.lock',
    ];

    public function loadPlugins() : void
    {
        $this->plugins = $this->scanPlugins();
    }

    public function getPlugins() : array
    {
        return $this->plugins;
    }

    public function pluginExists(string $pluginPath) : bool
    {
        return in_array($pluginPath, array_keys($this->plugins));
    }

    public function getPluginInfo(string $pluginPath) : array
    {
        return [
            'dir' => plugins_path($pluginPath),
            'name' => str_replace('/', '_', $pluginPath),
            'path' => $pluginPath
        ];
    }

    protected function shouldSkip($fileName) : bool
    {
        foreach (self::SKIP as $skipped) {
            if (strpos($fileName, $skipped) !== false or basename($fileName) === $skipped) {
                return true;
            }
        }

        return false;
    }

    public function export(string $pluginPath) : array
    {
        if ($this->pluginExists($pluginPath)) {

            $pluginInfo = $this->getPluginInfo($pluginPath);

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($pluginInfo['dir'], \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            $data = [];

            foreach ($iterator as $item) {

                $path = $item->getPathname();
                $relativePath = str_replace(plugins_path().'/', '', $path);

                // Исключаем composer.lock, .git и vendor
                if ($this->shouldSkip($path))
                    continue;

                $entry = [
                    'name' => $item->getFilename(),
                    'path' => $relativePath,
                    'type' => $item->isDir() ? 'directory' : 'file'
                ];

                if (!$item->isDir()) {
                    $content = file_get_contents($item->getPathname());

                    // Проверяем, можно ли сериализовать содержимое как raw
                    json_encode(['test' => $content]);

                    if (json_last_error() === JSON_ERROR_NONE) {
                        $entry['type'] = 'raw_file';
                        $entry['content'] = $content;
                    } else {
                        $entry['type'] = 'encoded_file';
                        $entry['content'] = base64_encode($content);
                    }

                    $entry['size'] = $item->getSize();

                    $data[] = $entry;
                }
            }

            return $data;
        }
        throw new \RuntimeException('Plugin not found');
    }

    public function import(string $json) : void
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Если JSON некорректен, возвращаем ошибку
            throw new \RuntimeException('Plugin json error: ' . json_last_error_msg());
        }

        foreach ($data as $item) {

            $fullPath = plugins_path($item['path']);

            // Создание директорий, если они не существуют
            $dir = dirname($fullPath);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            if (isset($item['content'])) {

                if ($item['type'] === 'raw_file')
                    $content = $item['content'];

                if ($item['type'] === 'encoded_file')
                    // Загрузите файл из base64 decode
                    $content = base64_decode($item['content'], true);

                file_put_contents($fullPath, $content);
            }
        }
    }

    protected function scanPlugins()
    {
        $plugins = [];

        $iterator = new \DirectoryIterator(plugins_path());

        foreach ($iterator as $authorsFolders) {

            if ($authorsFolders->isDir() && !$authorsFolders->isDot()) {

                $author = $authorsFolders->getFilename();

                foreach (new \DirectoryIterator($authorsFolders->getRealPath()) as $pluginFolder) {

                    if ($pluginFolder->isDir() && !$pluginFolder->isDot() && $pluginFolder != '.git') {

                        $plugin = $pluginFolder->getFilename();

                        $plugins[$author.'/'.$plugin] = $author.'.'.$plugin;
                    }
                }

            }
        }

        return $plugins;
    }
}
