<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\Config\Autorun\Model;

use Ge\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля записи автозапуска расширений.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\Config\Autorun\Model
 * @since 1.0
 */
class GridRow extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{panel_autorun}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['enabled'], // расширение доступно для запуска
                ['route'] // расширение
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
                if ($message['success']) {
                    $enabled = (int) $columns['enabled'];
                    $message['message'] = $this->module->t('Component with route «{0}» ' . ($enabled > 0 ? 'added to autorun' : 'removed from autorun'), [$this->route]);
                    $message['title']   = $this->t($enabled > 0 ? 'Added to autorun' : 'Removed from autorun');
                }
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
            });
    }
}
