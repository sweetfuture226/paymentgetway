var UITree = function () {

    var handleSample1 = function () {

        $('#tree_1').jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                }            
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-users icon-state-info icon-lg"
                },
                "default1" : {
                    "icon" : "fa fa-send icon-state-info icon-lg"
                },
                "file" : {
                    "icon" : "fa fa-check icon-state-info icon-lg"
                },
                "unbook" : {
                    "icon" : "fa fa-user-times icon-state-danger icon-lg"
                }
                ,
                "pending" : {
                    "icon" : "fa fa-spinner icon-state-warning icon-lg"
                }
            },
            "plugins": ["types"]
        });


    }

    return {
        //main function to initiate the module
        init: function () {
            handleSample1();
        }

    };

}();

if (App.isAngularJsApp() === false) {
    jQuery(document).ready(function() {    
       UITree.init();
    });
}