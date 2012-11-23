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
            triggerCancel: '.cancel',
            urlAction: ''

        };
        var o = $.extend({},defaults,options);
        o.params.id = o.id;

        var main = {
            initSortable: function(){
                $(o.tablesSrc + ', ' + o.fieldsDest).sortable({
                    connectWith: o.tabConnect,
                    placeholder: 'placeholder',
                    helper: 'clone',
                    start: function(event, ui){
                    },
                    beforeStop: function(){
                    },
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
            },
            setModuleContainer: function(elem){
                var moduleSrc = elem.parents('li.moduleItem');
                var moduleLabel = moduleSrc.children('span').text();
                var cloneSrc = moduleSrc.clone();
                var cloneId = cloneSrc.attr('id');
                var exists = $(o.tablesDest).find('li[id^=' + cloneId + ']');
                if (exists.length < 1)
                {
                    cloneSrc.attr('id', moduleSrc.attr('id') +'-dest');
                    cloneSrc.children('ul').children().remove();
                    cloneSrc.children('ul').attr('id', 'fieldsSelect');
                    cloneSrc.children('ul').attr('class', 'fieldsConnected');
                    cloneSrc.children('.moduleTitle').prepend('<span class="cancel">&nbsp;</span>');
                    cloneSrc.appendTo(o.tablesDest);
                    cloneSrc.disableSelection();
                    cloneSrc.children('ul').disableSelection();
                    main.initSortable();
                }
                return false;
            },
            toggleFields: function(){
                $(o.triggerFields).live('click', function(e){
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
            },
            cancelFields: function(){
                $(o.triggerCancel).live('click', function(e){
                    var container = $(this).parents('li.moduleItem');
                    var origin = $('#' + container.attr('id').replace('-dest', ''));
                    container.children(o.fieldsDest).children().appendTo(origin.children(o.tablesSrc));
                    container.remove();
                });
            }
        }

        main.initSortable();
        main.toggleFields();
        main.cancelFields();
        $('#tablesSrc .tableItem').mousedown(function(){
            main.setModuleContainer($(this));
        });
    }

})(jQuery);