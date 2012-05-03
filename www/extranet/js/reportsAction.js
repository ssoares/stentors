(function($) {
    $.fn.reports = function(options){
        var defaults = {
            id: 0,
            tablesSrc: '#tablesSrc',
            tablesDest: '#tablesSelec',
            tabConnect: '.tablesConnected',
            separator: '-',
            params:{
                'RE_TablesList' : '',
                'RE_FieldsList': ''
            },
            baseUrl: '',
            urlAction: ''

        };
        var o = $.extend({},defaults,options);
        o.params.id = o.id;

        var main = {
            initSortable: function(){
                $(o.tablesSrc + ', ' + o.tablesDest).sortable({
                    connectWith: '.tablesConnected',
                    placeholder: 'placeholder',
                    stop: function(){
                        main.saveTableList();
                    }
                }).disableSelection();
            },
            saveTableList: function(){
                var tablesList = $(o.tablesDest).children();
                var tmp = new Array();
                tablesList.each(function(){
                    var value = $(this).attr('id').split(o.separator)[0];
                    if (value.length > 0)
                        tmp.push(value);
                });
                o.params.RE_TablesList = tmp.join(o.separator);
                $.post(
                    o.baseUrl + o.urlAction,
                    o.params,
                    function(data){
                        
                    },
                    'json'
                );
            }

        }

        main.initSortable();
    }

})(jQuery);