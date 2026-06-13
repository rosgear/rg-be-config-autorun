<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * Пакет русской локализации.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

return [
    '{name}'        => 'Автозапуск',
    '{description}' => 'Автозапуск модулей и расширений',
    '{permissions}' => [
        'any'  => ['Полный доступ', 'Управление автозапуском модулей и расширений']
    ],

    // Grid: панель инструментов
    'Adding an extension route for autostart' => 'Добавление маршрута модуля / расширения в автозапуск',
    'Removing selected items from autorun' => 'Удаление выделенных маршрутов из автозапуска',
    'Are you sure you want to remove selected items from autorun?' => 'Вы действительно хотите удалить выделенные маршруты из автозапуска?',
    // Grid: контекстное меню
    'Edit record' => 'Редактировать',
    // Grid: столбцы
    'User role' => 'Роль пользователя',
    'Index' => 'Порядок',
    'Index number' => 'Порядковый номер запускаемого компонента',
    'Priority' => 'Приоритет',
    'Priority of the component to run (if the user has multiple roles)' => 'Приоритет запускаемого компонента (если пользователь имеет несколько ролей)',
    'Route' => 'Маршрут',
    'Launched component route' => 'Маршрут запускаемого компонента',
    'Component type' => 'Тип компонента',
    'Component type (module, extension)' => 'Тип компонента (модуль, расширение)',
    'Module / Extension' => 'Модуль / Расширение',
    'Autorun enabled' => 'Расширение доступно для автозапуска',
    'Plugin' => 'Плагин',
    'Module' => 'Модуль',
    // Grid: значения
    'yes' => 'да',
    'no' => 'нет',
    'module' => 'Модуль',
    'plugin' => 'Плагин',
    'widget' => 'Виджет',
    // Grid: всплывающие сообщения / заголовок
    'Added to autorun' => 'Добавлено в автозапуск',
    'Removed from autorun' => 'Изъято из автозапуска',
    // Grid: всплывающие сообщения / текст
    'Component with route «{0}» added to autorun' => 'Компонент с маршрутом «{0}» добавлено в автозапуск.',
    'Component with route «{0}» removed from autorun' => 'Компонент с маршрутом «{0}» изъят из автозапуска.',

    // Form
    '{form.title}' => 'Добавление маршрута запуска ',
    '{form.titleTpl}' => 'Изменение маршрута запуска "{route}"',
    // Form: поля
    'Add to autorun' => 'Добавить в автозапуск'
];
