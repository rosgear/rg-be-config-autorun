<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\Config\Autorun\Model;

use Ge\Panel\Data\Model\GridModel;

/**
 * Модель данных списка автозапуска модулей и их расширений.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\Config\Autorun\Model
 * @since 1.0
 */
class Grid extends GridModel
{
    /**
     * Модули / расширения.
     *
     * @var array
     */
    protected array $extensions = [];

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName' => '{{panel_autorun}}',
            'primaryKey' => 'id',
            'useAudit'   => true,
            'fields'     => [
                ['roleName', 'direct' => 'role.name'], // роль пользователя
                ['index'], // порядковый номер
                ['priority'], // приоритет
                ['enabled'], // расширение доступно для запуска
                ['route'], // маршрут
                /**
                 * поля добавленные в запросе:
                 * - roleName, имя пользователя
                 * 
                 * поля добавленные динамически:
                 * - extName, имя модуля / расширения
                 * - extDesc, описание модуля / расширения
                 * - extType, тип
                 */
            ],
            'order' => [
                'role_id' => 'ASC',
                'index'   => 'ASC'
            ],
            'resetIncrements' => ['{{panel_autorun}}']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Ge\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_SET_FILTER, function ($filter) {
                /** @var \Ge\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRow(array &$row): void
    {
        // заголовок контекстного меню записи
        $row['popupMenuTitle'] = $row['roleName'];
        // расширение
        $extension = $this->extensions[$row['route'] ?? ''] ?? null;
        if ($extension) {
            $row['extName'] = $extension['name'];
            $row['extDesc'] = $extension['desc'];
            $row['extIcon'] = $extension['icon'];
            $row['extType'] = $this->t($extension['type']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeFetchRows(): void
    {
        /** @var \Ge\Session\Container $storage */
        $storage = $this->module->getStorage();
        $this->extensions = $storage->extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): array
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS `autorun`.*, `role`.`name` `roleName` '
             . 'FROM `{{panel_autorun}}` `autorun` '
             . 'JOIN `{{role}}` `role` ON `role`.`id`=`autorun`.`role_id` ';
        return $this->selectBySql($sql);
    }
}
