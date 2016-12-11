Ext.define('Myapp.view.admin.role.SvgBox', {
    //extend: 'Ext.container.Container',
    extend: 'Ext.Component',
    //extend: 'Ext.panel.Panel',
    alias: 'widget.svgbox',

    initComponent: function(){
        var me = this;
        Ext.util.CSS.swapStyleSheet('svg_css', 'resources/css/svg.css');

        Ext.applyIf(me, {
            //layout: 'fit',
            //padding: '3 10',
            overflowX: 'hidden',
            loader: {
                loadMask: true,
                url: me.url,
                listeners: {
                    load: function(){
                        me.setSvgWidth(me);
                    }
                }
            },
            listeners: {
                render: function(cmp){
                    cmp.reload();
                },
                resize: function(box, width, hegiht) {
                    me.setSvgWidth(box, width);
                }
            }
        });

        me.callParent(arguments);
    },

    reload: function() {
        var me = this;
        me.getLoader().load();
    },

    setSvgWidth: function(box, width){
        var me = this;

        var boxInfo = box.getBox();
        width = boxInfo.width;

        var res = Ext.query('svg', box.el.dom);
        if(res.length > 0){
            var svg = res[0];
            var w = parseFloat(svg.style.width), h = parseFloat(svg.style.height);
            //console.log(me.url, boxInfo.width, boxInfo.height, w, h, width);
            svg.style.width = width*0.9 + 'px';
        }
    },

    activeTextNodes: function(permissionArr) {
        var me = this;
        var textNodes = Ext.query('svg text', me.el.dom);

        me.clearAllActive();
        if(textArr){
            Ext.Array.each(textNodes, function(node){
                console.log(me.getSvgTextID(node.textContent), 3434);
                if(Ext.Array.contains(textArr, node.textContent)) {
                    node.setAttribute('class', 'active');
                }
            });
        }
    },

    getSvgTextID: function (txt) {
        var match =  /#(\d+) /.exec(txt);
        return match ? match[1] : false;
    },

    getSvgTextRange: function (txt) {
        var match =  /\[(\d+):(\d+)\]/.exec(txt);
        return match ? [match[1], match[2]] : false;
    },

    clearAllActive: function(){
        var me = this;
        var oldActive = Ext.query('text.active', me.el.dom);
        if(oldActive.length) {
            Ext.Array.each(oldActive, function(o){
                o.setAttribute('class', '');
            });
        }
    }
});
