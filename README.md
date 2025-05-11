# October CMS Json Plugin Manager

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![OctoberCMS](https://img.shields.io/badge/OctoberCMS-3.x-orange.svg)](https://octobercms.com/)
[![OctoberCMS](https://img.shields.io/badge/OctoberCMS-1.x-orange.svg)](https://octobercms.com/)

Универсальный инструмент для управления плагинами October CMS через JSON. Импортируйте, экспортируйте и создавайте резервные копии плагинов без взаимодействия с базой данных.

## 🌟 Возможности
- **Импорт плагинов** из JSON-строки
- **Экспорт плагинов** в читаемый JSON-формат
- Рекурсивная обработка файлов и директорий
- Поддержка кириллицы и спецсимволов
- Валидация JSON при импорте

## 📦 Установка
1. Установите плагин через Artisan:
```bash
php artisan plugin:install Xakfull.JsonPluginManager

1. Или установите плагин через Composer:
```bash
composer require xakfull/jsonpluginmanager
