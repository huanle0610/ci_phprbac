Ext.define('Myapp.store.NavigationTree', {
    extend: 'Ext.data.TreeStore',

    storeId: 'NavigationTree',
    root: {
        expanded: true,
        children: [
            {
                text: 'Role',
                view: 'admin.role.RoleContainer',
                leaf: true,
                iconCls: 'fa fa-question',
                routeId:'faq'
            }
        ]
    },
    fields: [
        {
            name: 'text'
        }
    ]
});