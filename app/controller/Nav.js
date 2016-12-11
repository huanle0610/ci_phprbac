Ext.define('Myapp.controller.Nav', {
    extend: 'Ext.app.Controller',
    views: [],

    refs: [
        {
            ref: 'bell',
            selector: '#bell'
        }
    ],

    init: function() {
        var me = this;
        me.hideLoading();
        Ext.setGlyphFontFamily('FontAwesome');

        me.control({
            '#profile_img': {
                scope: me,
                render: 'profileImgRender',
                click: 'showProfile'
            },
            '#bell': {
                scope: me,
                render: 'afterBellRender'
            }
        });

        if(1) {
            Myapp.getApplication().getController('Main');
        }
    },

    hideLoading: function(){
        Ext.get('lpt').dom.style.width = '100%';
        Ext.defer(function(){
            Ext.get('loading').hide();
        }, 300);
    },

    // image 没有click事件， 对el的click事件转发
    profileImgRender: function(cmp){
        cmp.el.on({
            click: function(e) {
                cmp.fireEvent('click', cmp, e);
            }
        });
    },

    showProfile: function(cmp, e){
        var menu = Ext.create('Ext.menu.Menu', {
            items: [
                {
                   text: 'hello'
                }
            ]
        });

        e.preventDefault();
        menu.showAt(e.getXY()[0], cmp.getBox().bottom + 3);
    },

    showLogin: function(){
        Ext.create('Myapp.view.authentication.Login').show();
    },

    afterBellRender: function() {
        var me = this;
        Ext.defer(function(){
            me.locateHintNewMsg();
        }, 1000);
    },

    locateHintNewMsg: function(){
        var me = this;
        var bell = me.getBell();
        var pos = bell.getBox();
        var flaeQ = Ext.fly(Ext.query('.flaeQ')[0]);
        flaeQ.setStyle({
            top: (pos.top - 2) + 'px',
            left: (pos.right - 13) + 'px'
        });
    },

    // show bell red point hint
    // Myapp.getApplication().getController('Nav').toggleHintNewMsg(1)
    toggleHintNewMsg: function(show){
        var me = this;
        var flaeQ = Ext.fly(Ext.query('.flaeQ')[0]);
        flaeQ.setStyle({
            opacity: show ? 1 : 0
        });
    }
});
