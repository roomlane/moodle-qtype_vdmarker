YUI.add('moodle-qtype_vdmarker-vd', function(Y) {
    var VDMARKERVDNAME = 'vdmarker_vd';
    var VDMARKER_VD = function() {
        VDMARKER_VD.superclass.constructor.apply(this, arguments);
    }
    Y.extend(VDMARKER_VD, Y.Base, {
        initializer : function(config) { //'config' contains the parameter values
            alert('I am in initializer');
            
            var Y_topnode = Y.one('#' + this.get('topnode'));
            
            //! register the OnClick event
            Y_topnode.on('click', this.click, this);
        },
        click : function (e) {
            alert('click :)');
            //! mark an area
        }
    }, {
        NAME : VDMARKERVDNAME, //module name is something mandatory. 
                                //It should be in lower case without space 
                                //as YUI use it for name space sometimes.
        ATTRS : {
                 topnode : {value : null},
                 state : {value : null},
                 fieldtoupdate : {value : null},
                 circles : {value : null}
        } // Attributs are the parameters sent when the $PAGE->requires->yui_module calls the module. 
          // Here you can declare default values or run functions on the parameter. 
          // The param names must be the same as the ones declared 
          // in the $PAGE->requires->yui_module call.
    });
    M.qtype_vdmarker = M.qtype_vdmarker || {}; //this line use existing name path if it exists, ortherwise create a new one. 
                                                 //This is to avoid to overwrite previously loaded module with same name.
    M.qtype_vdmarker.init_vd = function(config) { //'config' contains the parameter values
        alert('I am in the javascript module, Yeah!');
        return new VDMARKER_VD(config); //'config' contains the parameter values
    }
  }, '@VERSION@', {
      requires:['base', 'node']
  });