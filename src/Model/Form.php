<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\Config\Autorun\Model;

use Ge;
use Ge\Panel\Data\Model\FormModel;

/**
 * Модель данных автозапуска расширений.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\Config\Autorun\Model
 * @since 1.0
 */
class Form extends FormModel
{
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
                ['id'],
                ['role_id', 'alias' => 'roleId'], // роль пользователя
                ['index'], // порядок
                ['priority'], // приоритет
                ['enabled'], // расширение доступно для запуска
                ['route'], // маршрут
                /**
                 * поля добавленные динамически:
                 * - moduleRoute, маршрут модуля
                 */
            ],
            // правила форматирования полей
            'formatterRules' => [
                [['enabled'], 'logic'],
                [['route'], 'safe']
            ],
            // правила валидации полей
            'validationRules' => [
                [['route'], 'notEmpty'],
                // порядковый номер
                [
                    'index', 
                    'between',
                    'min' => 1, 'max' => PHP_INT_MAX
                ],
                // приоритет
                [
                    'priority', 
                    'between',
                    'min' => 1, 'max' => PHP_INT_MAX
                ],
                // маршрут
                [
                    'route',
                    'between',
                    'min' => 2, 'max' => 255, 'type' => 'string'
                ]
            ]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                /** @var \Ge\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_DELETE, function ($result, $message) {
                /** @var \Ge\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * Возвращает значение для выпадающего списка ролей пользователей.
     * 
     * @param null|int|string $itemId Идентификатор роли пользователя.
     * 
     * @return array|null
     */
    protected function getUserRoleComboItem($itemId): ?array
    {
        if ($itemId) {
            /** @var \Rg\Backend\Role\Model\Role|null $roleAR */
            $roleAR = Ge::$app->modules->getModel('Role', 'rg.be.user_roles');
            if ($roleAR) {
                $row = $roleAR->fetchByPk($itemId);
                return $row[0]  ?? null;
            }
            return null;
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        // значение выпадающего списка по умолчанию
        $emptyComboItem = [
            'type'  => 'combobox',
            'value' => 'null',
            'text'  => Ge::t(BACKEND, '[None]')
        ];
        // определяем имя модуля по указанному маршруту
        $this->moduleRoute = $this->route;

        /** @var array $item роль пользователя */
        $item = $this->getUserRoleComboItem($this->roleId);
        if ($item) {
            $this->roleId = [
                'type'  => 'combobox',
                'value' => $item['id'],
                'text'  => $item['name']
            ];
        } else
            $this->roleId = $emptyComboItem;
    }
}
