// connect Ext.Ajax enent to Bus
Ext.define('MyApp.domain.Ajax', {
    extend: 'Ext.app.EventDomain',
    singleton: true,
    type: 'extAjax',
    idProperty: 'myRequest',
    constructor: function () {
        var me = this;
        me.callParent();
        me.monitor(Ext.Ajax);
    }
});
Ext.define('Myapp.controller.Main', {
    extend: 'Ext.app.Controller',
    views: [],

    refs: [
        {
            ref: 'viewport',
            selector: 'viewport'
        },
        {
            ref: 'bell',
            selector: '#bell'
        },
        {
            ref: 'centerPanel',
            selector: '#centerPanel'
        },
        {
            ref: 'debugPanel',
            selector: '#debug_panel'
        }
    ],

    tokenDelimiter: ':',

    init: function () {
        var me = this;
        Ext.History.init();

        me.control({
            '#centerPanel': {
                scope: me,
                tabchange: 'onTabChange',
                afterrender: 'onAfterRender'
            },
            '#closeWin': {
                scope: me,
                click: Func.closeWin
            },
            '#nav_tree': {
                scope: me,
                itemclick: 'treeItemClick'
            },
            '#navTreeSetting': {
                scope: me,
                click: 'navTreeSetting'
            },
            '#unpin_debug': {
                scope: me,
                click: 'showDebugWin'
            }
        });
    },

    onTabChange: function (tabPanel, tab) {
        var me = this;
        var tabs = [],
            ownerCt = tabPanel.ownerCt,
            oldToken, newToken;

        tab && tabs.push(tab.itemId);
        tabs.push(tabPanel.itemId);

        if (tab && tab.store) {
            tab.store.reload();
        }

        while (ownerCt && ownerCt.is('tabpanel')) {
            tabs.push(ownerCt.itemId);
            ownerCt = ownerCt.ownerCt;
        }

        newToken = tabs.reverse().join(me.tokenDelimiter);

        oldToken = Ext.History.getToken();

        if (oldToken === null || oldToken.search(newToken) === -1) {
            Ext.History.add(newToken);
        }
    },

    onAfterRender: function () {
        var me = this;

        Ext.History.on('change', function (token) {
            var parts, length, i;

            if (token) {
                parts = token.split(me.tokenDelimiter);
                length = parts.length;

                console.log(length);
                // setActiveTab in all nested tabs
                me.getCenterPanel().setActiveTab(parts[i + 1]);
            }
        });

        // This is the initial default state.  Necessary if you navigate starting from the
        // page without any existing history token params and go back to the start state.
        var activeTab1 = me.getCenterPanel().getActiveTab(),
            activeTab2 = null;

        me.onTabChange(activeTab1, activeTab2);
    },

    treeItemClick: function (treeview, record, item, index, e) {
        var me = this;
        console.log(record, record.get('text'), record.get('id'), record.raw.routeId, record.raw.selectable, record.raw.view);

        var selectable = record.raw.selectable,
            routeId = record.raw.routeId,
            iconCls = record.raw.iconCls,
            closable = record.raw.closable,
            view = record.raw.view,
            cls = 'Myapp.view.' + view;

        if (false === selectable) {
            console.log('not a view');
            return;
        }

        var centerPanel = me.getCenterPanel();

        var tab = centerPanel.down('#' + routeId);
        if (!tab) {
            tab = Ext.create(cls, {
                iconCls: iconCls,
                closable: closable,
                itemId: routeId,
                title: record.get('text')
            });
            me.getController(Ext.String.capitalize(view.split('.')[0]));
            centerPanel.add(tab);
        }
        centerPanel.setActiveTab(routeId);
    },

    navTreeSetting: function () {
        var treeStore = Ext.create('Ext.data.TreeStore', {
            proxy: {
                type: 'direct',
                directFn: 'Navigation.getDemoTree',
                reader: {
                    type: 'json',
                    root: 'children'
                }
            },
            listeners: {
                beforeload: function () {
                    Func.showLoading();
                },
                load: {
                    fn: function () {
                        Func.hideLoading();
                        showWin();
                    },
                    single: true
                }
            }
        });

        treeStore.load();

        var showWin = function () {
            var w = Ext.create('Myapp.view.nav.NavSettingWin', {
                treeStore: treeStore,
                title: '自定义导航'
            });
            w.setSize(800, 400).center().show();
        };
    }
});
