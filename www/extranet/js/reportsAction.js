(function($) {
    $.fn.reports = function(options){
        var defaults = {
            id: 0,
            tablesSrc: '#fieldsSrc',
            tablesDest: '#tablesSelec',
            fieldsDest: '#fieldsSelect',
            tabConnect: '.fieldsConnected',
            separator: '-',
            params:{
                'RE_TablesList' : '',
                'RE_FieldsList': ''
            },
            baseUrl: '',
            triggerFields: '.moduleTitle',
            urlAction: ''

        };
        var o = $.extend({},defaults,options);
        o.params.id = o.id;

        var main = {
            initSortable: function(){
                $(o.tablesSrc + ', ' + o.fieldsDest).sortable({
                    connectWith: o.tabConnect,
                    placeholder: 'placeholder',
                    start: function(event, ui){
                    },
                    beforeStop: function(){
                    },
                    stop: function(){
//                        main.saveTableList();
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
            },
            setModuleContainer: function(elem){
                var moduleSrc = elem.parents('li.moduleItem');
                var moduleLabel = moduleSrc.children('span').text();
                var cloneSrc = moduleSrc.clone();
                cloneSrc.attr('id', moduleSrc.attr('id') +'-dest');
                var cloneId = cloneSrc.attr('id');
                var exists = $(o.tablesDest).find('#' + cloneId);
                if (exists.length < 1)
                {
                    cloneSrc.children('ul').children().remove();
                    cloneSrc.children('ul').attr('id', 'fieldsSelect');
                    cloneSrc.children('ul').attr('class', 'fieldsConnected');
                    cloneSrc.appendTo(o.tablesDest);
                    cloneSrc.disableSelection();
                    cloneSrc.children('ul').disableSelection();
                    main.initSortable();
                }
            },
            toggleFields: function(){
                $(o.triggerFields).click(function(){
                    var fields = $(this).next('ul');
                    if ($(this).hasClass('closed'))
                    {
                        $(this).removeClass('closed')
                        $(this).addClass('opened')
                        fields.fadeIn();
                    }
                    else if ($(this).hasClass('opened'))
                    {
                        $(this).removeClass('opened')
                        $(this).addClass('closed')
                        fields.fadeOut();

                    }
                });
            }
        }

        main.initSortable();
        main.toggleFields();
        $('.tableItem').mousedown(function(){main.setModuleContainer($(this));});
    }

})(jQuery);