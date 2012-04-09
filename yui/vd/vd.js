YUI.add('moodle-qtype_vdmarker-vd', function(Y) {
    var VDMARKERVDNAME = 'vdmarker_vd';
    var VDMARKER_VD = function() {
        VDMARKER_VD.superclass.constructor.apply(this, arguments);
    }
    Y.extend(VDMARKER_VD, Y.Base, {
        Y_topnode : null,
        state : null,
        dom_fieldtoupdate : null,
        circles : null,
        areas : null,
        initializer : function(config) { //'config' contains the parameter values
            alert('I am in initializer');
            
            var topnode = this.get('topnode');
            this.Y_topnode = Y.one('#' + topnode);
            this.state = this.get('state');
            this.dom_fieldtoupdate = document.getElementById(this.get('fieldtoupdate'));
            this.circles = this.get('circles');
            this.areas = Math.pow(2, this.circles.cnt);
            
            this.draw();
            this.hide_loading();
            
            this.Y_topnode.on('click', this.click, this);
        },
        hide_loading : function() {
            var img_loading = this.Y_topnode.one('img.vd-overlay#loading');
            if (img_loading) {
                img_loading.hide();
            }
        },
        circles_encoded : function(x, y) {
            var res = 0;
            var radius = this.circles.radius;
            for (var i = 0; i < this.circles.cnt; i++)
            {
                var dx = Math.abs(x - circles[i][0]);
                var dy = Math.abs(y - circles[i][1]);
                if (radius * radius >= dx * dx + dy * dy) {
                res += Math.pow(2, i);
                }
            }
            return res;
        },
        toggle_state_by_offset : function(x, y) {
            var substate = 1 << this.circles_encoded(x, y);
            this.state = this.state ^ substate;
        },
        click : function(e) {
            //TODO: is there a yui normalization for the relative position?
            var pos_x = e.offsetX?(e.offsetX):e.pageX-e.currenttarget.offsetLeft; //! offset Left not defined
            var pos_y = e.offsetY?(e.offsetY):e.pageY-e.currenttarget.offsetTop;
            
            this.toggle_state_by_offset(pos_x, pos_y);
            
            this.draw();
            this.save_to_field();
        },
        
        /**
         * Draws the state
         */
        draw : function() {
            var areastates = this.state_to_areastates(this.state);
            for (var i = 0; i < this.areas; i++) {
                //TODO: cache the nodes in an array in init..
                var overlay = this.Y_topnode.one('img.vd-overlay#ov' + i);
                if (areastates[i]) {
                    overlay.show();
                } else {
                    overlay.hide();
                }
            }
        },
        state_to_areastates : function(state) {
            var a = new Array();
            var one = 1;
            for (var i = 0; i < this.areas; i++) {
                if (state & one) {
                    a[i] = true;
                } else {
                    a[i] = false;
                }
                one = one << 1;
            }
            return a;
        },
        save_to_field : function() {
            if (this.dom_fieldtoupdate) {
                this.dom_fieldtoupdate.value = this.state;
            }
        }
    }, {
        NAME : VDMARKERVDNAME, //module name is something mandatory. 
        //It should be in lower case without space 
        //as YUI use it for name space sometimes.
        ATTRS : {
            topnode : {
                value : null
            },
            state : {
                value : 255
            },
            fieldtoupdate : {
                value : null
            },
            circles : {
                value : null
            }
        } // Attributs are the parameters sent when the $PAGE->requires->yui_module calls the module. 
    // Here you can declare default values or run functions on the parameter. 
    // The param names must be the same as the ones declared 
    // in the $PAGE->requires->yui_module call.
    });
    M.qtype_vdmarker = M.qtype_vdmarker || {}; //this line use existing name path if it exists, ortherwise create a new one. 
    //This is to avoid to overwrite previously loaded module with same name.
    M.qtype_vdmarker.init_vd = function(config) { //'config' contains the parameter values
        //alert('I am in the javascript module, Yeah!');
        return new VDMARKER_VD(config); //'config' contains the parameter values
    }
}, '@VERSION@', {
    requires:['base', 'node']
});