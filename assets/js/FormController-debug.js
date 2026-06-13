/*!
 * Контроллер формы.
 * Расширение "Автозапуск".
 * Модуль "Конфигурация".
 * Copyright 2015 RosGear. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://rosgear.ru/license/
 */

Ext.define('Rg.be.config.autorun.FormController', {
    extend: 'Ge.view.form.PanelController',
    alias: 'controller.rg-be-config-autorun-form',

    /**
     * Правка маршрута модуля / расширения.
     * @param {Ext.form.field.TextField} me
     * @param {Ext.event.Event} e
     * @param {Object} eOpts
     */
    keydownRoute: function (me, e, eOpts) {
        this.getViewCmp('modules').setValue(me.value);
    },

    /**
     * Выбор модуля из списка.
     * @param {Ext.form.field.ComboBox} combo
     * @param {Ext.data.Model|Ext.data.Model[]} record
     * @param {Object} eOpts
     */
     selectModule: function (combo, record, eOpts) {
         this.getViewCmp('route').setValue(record.get('route'));
    }
});
