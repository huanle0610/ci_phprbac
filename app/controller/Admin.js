Ext.define('Myapp.controller.Admin', {
    extend: 'Ext.app.Controller',

    refs: [
        {
            ref: 'bell',
            selector: '#bell'
        },
        {
            ref: 'centerPanel',
            selector: '#centerPanel'
        }
    ],

    init: function() {
        var me = this;

        me.control({
            '#modifyDesc': {
                scope: me,
                click: 'modifyDesc'
            },
            'admin_rolebox': {
                scope: me,
                roleClick: 'roleClick',
                permissionClick: 'permissionClick',
                pairChange: 'onPairChange',
                pairChangeFinish: 'onPairChangeFinish'
            },
            '#queryRolePerm': {
                scope: me,
                click: 'queryRolePerm'
            },
            '#bindRolePerm': {
                scope: me,
                click: 'bindRolePerm'
            },
            '#unBindRolePerm': {
                scope: me,
                click: 'unBindRolePerm'
            },
            '#addChild': {
                scope: me,
                click: 'addChild'
            }
        });
    },

    modifyDesc: function (item) {

        console.log(item.opId);

    },

    addChild: function(cmp){
        var opId = cmp.up('menu').opId;
        Ext.Msg.show({
            title: '添加权限',
            msg:  '父级 ' +  opId,
            width: 300,
            buttons: Ext.Msg.OKCANCEL,
            multiline: true,
            fn: function(btn, val){
                if(btn == 'ok') {
                    console.log(btn, val);
                    var txt = Ext.String.trim(val);
                    if(txt) {
                        var func = 'add' +  Ext.String.capitalize(cmp.up('menu').origin.business);
                        Rbac[func]({
                            pid: opId,
                            path: txt,
                            desc: ''
                        }, function(r){
                            console.log(r);
                            cmp.up('menu').origin.reload();
                        })
                    }
                }
            }
        });
    },

    roleClick: function(id, container){
        var permBox = container.down('#svg_box_perm');
        var rolePath = container.down('#rolePath');
        var roleTitle = container.down('#roleTitle');
        var permText = container.down('#permText');
        var roleDesc = container.down('#roleDesc');

        container.getEl().mask('查询中...');
        container.fireEvent('pairChange', container);
        Rbac.getRolePermissions({id: id}, function(r){
            container.getEl().unmask();
            if(!r.suc) {
                Ext.Msg.alert('Tip', r.msg);
                return;
            }

            rolePath.setValue(r.path);
            roleTitle.setValue(r.title);
            if(r.hasPermission){
                permBox.activeTextNodes(r.permissions);
                permText.setValue(r.permissionTexts.join(', '));
                roleDesc.setValue(r.roleDesc);
            } else {
                permBox.activeTextNodes();
                permText.setValue('无');
                roleDesc.setValue('');
            }
            container.fireEvent('pairChangeFinish', container);
        });
    },

    permissionClick: function(id, container){
        var permPath = container.down('#permPath');
        var permTitle = container.down('#permTitle');
        var permDesc = container.down('#permDesc');

        container.getEl().mask('查询中...');
        container.fireEvent('pairChange', container);
        Rbac.getPermissionPath({id: id}, function(r){
            container.getEl().unmask();
            if(!r.suc) {
                Ext.Msg.alert('Tip', r.msg);
                return;
            }

            permTitle.setValue(r.title);
            permPath.setValue(r.path);
            permDesc.setValue(r.permDesc);
            container.fireEvent('pairChangeFinish', container);
        });
    },

    //权限或角色被改变
    onPairChange: function(cmp){
        var bindBtn = cmp.down('#bindRolePerm');
        var unBindBtn = cmp.down('#unBindRolePerm');

        bindBtn.disable();
        unBindBtn.disable();
    },

    //权限或角色被改变
    onPairChangeFinish: function(cmp){
        var me = this;
        var autoQuery = cmp.down('#autoQuery').checked;
        if(autoQuery) {
            me.queryRolePerm(cmp.down('#queryRolePerm'));
        }
    },

    queryRolePerm: function(cmp){
       var up =  cmp.up('#detailPanel');
        var rolePath = up.down('#rolePath').getValue();
        var permPath = up.down('#permPath').getValue();
        var bindBtn = up.down('#bindRolePerm');
        var unBindBtn = up.down('#unBindRolePerm');

        var resField = up.down('#resField');

        if(rolePath && permPath) {
            up.getEl().mask('查询角色是否拥有权限...');
            resField.update({res: '查询中...'});
            Rbac.checkRoleHasPerm({rolePath: rolePath, permPath: permPath}, function(r){
                up.getEl().unmask();
                if(!r.suc) {
                    Ext.Msg.alert('Tip', r.msg);
                    return;
                }

                resField.update({res: r.hasPerm ? '有': '没有'});

                if(!r.hasPerm){
                    bindBtn.enable();
                    unBindBtn.disable();
                } else {
                    bindBtn.disable();
                    unBindBtn.enable();
                }
            });
        }
    },

    bindRolePerm: function(cmp){
        var up =  cmp.up('#detailPanel');
        var rolePath = up.down('#rolePath').getValue();
        var permPath = up.down('#permPath').getValue();
        var container = cmp.up('admin_rolebox');

        console.log(rolePath, permPath);
        Rbac.assign({rolePath: rolePath, permPath: permPath}, function(r){
            up.getEl().unmask();
            container.fireEvent('pairChangeFinish', container);
            if(!r.suc) {
                Ext.Msg.alert('Tip', r.msg);
                return;
            } else {
                Ext.Msg.alert('Tip', r.msg);
            }
        });
    },

    unBindRolePerm: function(cmp){
        var up =  cmp.up('#detailPanel');
        var rolePath = up.down('#rolePath').getValue();
        var permPath = up.down('#permPath').getValue();
        var container = cmp.up('admin_rolebox');

        console.log(rolePath, permPath);
        Rbac.unassign({rolePath: rolePath, permPath: permPath}, function(r){
            up.getEl().unmask();
            container.fireEvent('pairChangeFinish', container);
            if(!r.suc) {
                Ext.Msg.alert('Tip', r.msg);
                return;
            } else {
                Ext.Msg.alert('Tip', r.msg);
            }
        });
    }
});
