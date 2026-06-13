<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\Config\Autorun\Controller;

use Ge\Mvc\Module\BaseModule;
use Ge\Panel\Helper\ExtCombo;
use Ge\Panel\Widget\EditWindow;
use Ge\Panel\Widget\Form as WForm;
use Ge\Panel\Controller\FormController;

/**
 * Контроллер формы автозапуска модуля / расширений.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\Config\Autorun\Controller
 * @since 1.0
 */
class Form extends FormController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Rg\Backend\Config\Autorun\Extension
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var EditWindow $window */
        $window = parent::createWidget();

        // панель формы (Ge.view.form.Panel GeJS)
        $window->form->autoScroll = true;
        $window->form->router->route = $this->module->route('/form');
        $window->form->bodyPadding = 10;
        $window->form->controller = 'rg-be-config-autorun-form';
        $window->form->defaults = [
            'labelAlign' => 'right',
            'labelWidth' => 150
        ];
        $window->form->setStateButtons(WForm::STATE_UPDATE, ['info', 'save', 'delete', 'cancel']);
        $window->form->loadJSONFile('/form', 'items', [
            // выпадающий список модулей
            '@moduleCombobox' => ExtCombo::modules('#Module', 'moduleRoute', 'route', [
                'id'        => 'rg-config-autorun-form__modules',
                'listeners' => [
                    'select' => 'selectModule'
                ]
            ]),
            '@rolesCombobox' => ExtCombo::remote('#User role', 'roleId', [
                'proxy' => [
                    'url' =>  ['user-roles/trigger/combo', 'backend'],
                    'extraParams' => [
                        'combo' => 'role',
                        'noneRow' => 0
                    ]
                ]
            ], [
                'xtype'      => 'g-field-combobox',
                'allowBlank' => false
            ])
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->width = 500;
        $window->autoHeight = true;
        $window->resizable = false;
        $window
            ->setNamespaceJS('Rg.be.config.autorun')
            ->addRequire('Rg.be.config.autorun.FormController' . (GE_DEBUG ? '-debug' : ''));
        return $window;
    }
}
