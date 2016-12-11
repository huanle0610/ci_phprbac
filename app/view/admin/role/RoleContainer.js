Ext.define('Myapp.view.admin.role.RoleContainer', {
    extend: 'Ext.container.Container',
    alias: 'widget.admin_rolebox',

    requires: ['Myapp.view.admin.role.SvgBox'],

    initComponent: function () {
        var me = this;

        Ext.util.CSS.swapStyleSheet('role_css', 'resources/css/role.css');
        Ext.applyIf(me, {
            layout: {
                type: 'hbox',
                align: 'stretch'
            },
            items: [
                {
                    xtype: 'container',
                    flex: 2,
                    layout: {
                        type: 'vbox',
                        align: 'stretch'
                    },
                    items: [
                        {
                            xtype: 'svgbox',
                            itemId: 'svg_box_role',
                            flex: 1,
                            overflowY: 'auto',
                            business: 'role',
                            url: 'welcome/rbac/role'
                        },
                        {
                            xtype: 'splitter'
                        },
                        {
                            xtype: 'svgbox',
                            itemId: 'svg_box_perm',
                            flex: 1,
                            overflowY: 'auto',
                            business: 'permission',
                            url: 'welcome/rbac/permission'
                        }
                    ]
                },
                {
                    xtype: 'splitter'
                },
                {
                    xtype: 'panel',
                    flex: 1,
                    itemId: 'detailPanel',
                    bodyPadding: '6',
                    layout: {
                        type: 'vbox',
                        align: 'stretch'
                    },
                    items: [
                        {
                            xtype: 'fieldcontainer',
                            fieldLabel: '当前角色节点',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [
                                {
                                    xtype: 'textfield',
                                    flex: 1,
                                    readOnly: true,
                                    itemId: 'roleTitle'
                                },
                                {
                                    xtype: 'textfield',
                                    margin: '0 0 0 9',
                                    flex: 1,
                                    readOnly: true,
                                    itemId: 'rolePath'
                                }
                            ]
                        },
                        {
                            xtype: 'displayfield',
                            fieldLabel: '直接权限',
                            itemId: 'permText'
                        },
                        {
                            xtype: 'displayfield',
                            fieldLabel: '角色说明',
                            itemId: 'roleDesc'
                        },
                        {
                            xtype: 'fieldcontainer',
                            fieldLabel: '当前权限节点',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [
                                {
                                    xtype: 'textfield',
                                    flex: 1,
                                    readOnly: true,
                                    itemId: 'permTitle'
                                },
                                {
                                    xtype: 'textfield',
                                    margin: '0 0 0 9',
                                    flex: 1,
                                    readOnly: true,
                                    itemId: 'permPath'
                                }
                            ]
                        },
                        {
                            xtype: 'displayfield',
                            fieldLabel: '权限说明',
                            itemId: 'permDesc'
                        },
                        {
                            xtype: 'container',
                            layout: 'hbox',
                            items: [
                                {
                                    xtype: 'button',
                                    itemId: 'queryRolePerm',
                                    text: '查询角色是否有此权限'
                                },
                                {
                                    xtype: 'checkbox',
                                    checked: true,
                                    margin: '0 0 0 9',
                                    boxLabel: '条件改变，自动查询',
                                    itemId: 'autoQuery'
                                },
                                {
                                    xtype: 'box',
                                    cls: 'resField',
                                    margin: '0 0 0 9',
                                    tpl: '结果：{res}',
                                    itemId: 'resField'
                                }
                            ]
                        },
                        {
                            xtype: 'container',
                            layout: 'hbox',
                            margin: '5 0 0 0',
                            items: [
                                {
                                    xtype: 'button',
                                    disabled: true,
                                    itemId: 'bindRolePerm',
                                    iconCls: 'fa fa-check-square-o',
                                    text: '赋予角色该权限'
                                },
                                {
                                    xtype: 'button',
                                    disabled: true,
                                    margin: '0 0 0 9',
                                    itemId: 'unBindRolePerm',
                                    iconCls: 'fa fa-square-o',
                                    text: '废除角色该权限'
                                }
                            ]
                        }

                    ]
                }
            ]

        });

        me.callParent(arguments);
    },

    afterRender: function () {
        var me = this;
        me.callParent(arguments);
        me.getEl().on("click", Ext.bind(me.onSvgEvent, me, ['hello'], true), this, {
            preventDefault: true,
            delegate: "svg"
        }).on("contextmenu", me.onSvgEvent, this, {
            preventDefault: true,
            delegate: "svg"
        });
    },


    onSvgEvent: function (e, dom) {
        e.preventDefault();

        var eventName = e.type;

        var me = this;
        var target = e.target;

        var svgbox = Ext.getCmp(Ext.fly(dom).up('div').id);

        if (target.tagName == 'text') {
            var id = svgbox.getSvgTextID(target.textContent);
            if (!id) {
                console.log('无ID无法操作');
                return;
            }

            svgbox.clearAllActive();

            target.setAttribute('class', 'active');

            if (eventName == 'contextmenu') {
                me.menu = me.menu || Ext.create('Ext.menu.Menu', {
                        items: [
                            {
                                text: '添加子节点',
                                itemId: 'addChild'
                            },
                            {
                                text: '查看相关角色/权限',
                                itemId: 'viewOther'
                            },
                            {
                                text: '查看相关用户',
                                itemId: 'viewUsers'
                            },
                            {
                                text: '修改备注',
                                itemId: 'modifyDesc'
                            },
                            {
                                text: '生成检查该权限的代码',
                                itemId: 'getPhpCode'
                            },
                            {
                                text: '删除节点',
                                itemId: 'delNode'
                            }
                        ]
                    });
                me.menu.origin = svgbox;
                me.menu.opId = id;
                me.menu.showAt(e.getXY());
            } else if (eventName == 'click') {
                me.fireEvent(svgbox.business + 'Click', id, me);
            }
        }
    },

    onElMouseover: function (event) {
        // In this case, we can easily find the Component,
        // because el will have the same id as the Panel.
        // Performance will not be a problem either
        // since Ext.getCmp() lookups are very fast.
        var cmp = Ext.getCmp(this.id);

        // Now we can fire a Component event that Controllers
        // will see and can react to.
        if (cmp) {
            cmp.fireEvent('myPanelMouseover', cmp, event);
        }
        // The code between <debug></debug> tags will be removed
        // from production code by Sencha Cmd compiler, so this
        // is "dev eyes only"
        //<debug>
        else {
            Ext.Error.raise('mouseover handler has Element ' +
                'with id ' + this.id + ', but no ' +
                'matching Component found!');
        }
        //</debug>
    }
});
