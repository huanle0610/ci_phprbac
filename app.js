Ext.Loader.setConfig({
    enabled: true,
    paths:{
        'Ext.ux': 'app/ux',
        'Myapp': 'app'
    }
});

var Ecq = function (selector, dn) {
    return Ext.ComponentQuery.query(selector, dn);
};
var Downq = function(selector, dn) { return Ecq(selector, dn)[0] };

var forceExit = function(msg){ throw new Error(msg || 'Force to stop js continue.'); };

Ext.override(Ext.Window, {
    constrainHeader: true
});

// ext store remote filter missing operator fix
Ext.override(Ext.data.proxy.Server, {encodeFilters: function(filters) {
    var min = [],
        length = filters.length,
        i = 0;

    for (; i < length; i++) {
        if(filters[i].property && filters[i].value){
            min[i] = {
                operator: filters[i].operator,
                property: filters[i].property,
                value   : filters[i].value
            };
        }
    }
    return this.applyEncoding(min);
}});

Ext.define('Func', {
    singleton: true,
    log: function(msg) {
        console.log(msg);
    },
    getController: function(controller){
        Myapp.getApplication().getController(controller);
    },
    showLoading: function(msg){
        msg = msg || 'loading...';
        Downq('viewport').getEl().mask(msg);
    },

    hideLoading: function(){
        Downq('viewport').getEl().unmask();
    },

    closeWin: function(cmp){
        if(cmp.isWindow){
            cmp.close();
        } else {
            var win = cmp.up('window');
            if(win){
                win.close();
            }
        }
    }
});

Ext.direct.Manager.addProvider(Ext.app.REMOTING_API);
Ext.application({
    name: 'Myapp',

    appFolder: 'app',
    requires: [],
    stores: [
        'NavigationTree'
    ],
    controllers: [
        'Nav', 'Admin'
    ],

    init: function(){
        if(Ext.supports.LocalStorage){
            Ext.state.Manager.setProvider(new Ext.state.LocalStorageProvider());
        }else{
            Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
                expires: (new Date(new Date().getTime() + (86400*100)))
            }));
        }
    },

    autoCreateViewport: true
});