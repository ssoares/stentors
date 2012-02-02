(function($) {
    $.fn.profile = function(options){
        var defaults = {
            infosTab: $('#infosTab'),
            profilesTab: $('#profilesTab'),
            confirmBox: $("#dialog-confirm"),
            addBtn: $('#addTab'),
            saveBtn: $('#submitSave'),
            profilesLst : $('.profiles'),
            addTrigger : $('.addProfile'),
            delTrigger : $('.deleteTab'),
            infos : $('.emptyModel'),
            zoneWidth: 510,
            easingEffect: 'easeInExpo',
            widthResize: 450,
            resized : false,
            params:{},
            baseUrl: null,
            url: '/users/index/ajax/',
            id: 0,
            imgDel: '',
            separator: ', ',
            blank: ' ',
            errors: 0,
            btnLblCancel: 'Annuler',
            btnLblValid: 'Valider',
            logValues:{nbChanges : 0},
            dispProfiles : false
        };
        var o = $.extend({},defaults,options);
        o.profilesTab.tabs( "option", "cache", true );
//        o.profilesLst.children().children().live('click', function(e){
//            e.preventDefault()
//        });

        var addTab = {
            add: function(){
                o.addTrigger.live('click', function(e){
                    e.preventDefault();
                    var elem = $(this);
                    var profile = $(this).attr('href');
                    var url = o.baseUrl + o.url;
                    o.params = {
                        op: 'add',
                        genericId: o.id,
                        profile: profile
                    };
                    $.post(
                        url,
                        o.params,
                        function(data){
                            if (data)
                            {
                                elem.hide();
                                addTab.displayTab(profile, data.tabTitle);
                                main.changeTab();
                            }
                        },
                        'json'
                    );

                });
            },
            displayTab: function(profile, tabTitle){
                var title = tabTitle ;
                var url = o.baseUrl + '/users/index/' + profile + '/actionKey/edit/id/' + o.id + '/';
                var index = main.getIndex(o.profilesLst.children('li'), profile) + 1;
                var nbTabs = o.profilesTab.children('ul').children('li').length;
                if (index > nbTabs)
                    index = nbTabs;
                o.profilesTab.tabs("add", url, title, index);
                o.profilesTab.tabs("select", index);
                $('.ui-state-active a:first').attr('id', profile);
                $('.ui-state-active a:first').after('<a class="deleteTab ' + profile + '">' + o.imgDel + '</a>');

            },
            toggle: function(){
                var elem = o.addBtn;
                $(o.profilesLst).mouseenter(function(){
//                    $(this).slideDown();
                    o.addBtn.addClass('btnHover');
                    o.dispProfiles = false;
                });

                $(o.profilesLst).mouseleave(function(){
                    if (o.dispProfiles)
                    {
                        o.addBtn.removeClass('btnHover');
                        o.profilesLst.slideUp('slow');
                    }

                    o.dispProfiles = false;
                });
                elem.mouseover(function(){
                    if (!o.profilesLst.is(':visible'))
                    {
                        o.profilesLst.slideDown('slow');
                        o.dispProfiles = true;
                    }

                });
                elem.mouseleave(function(e){
                    if (!o.dispProfiles || e.type == 'mouseleave')
                    {
                        o.profilesLst.slideUp('slow');
                        o.addBtn.removeClass('btnHover');
                    }
                    else
                        o.dispProfiles = false;
                });
            },
            _resize: function(profil, infos){
                if (profil.width() <= o.zoneWidth)
                    o.resized = true;

                if (!o.resized)
                {
                   profil.animate(
                   {
                       width: '-=' + o.widthResize
                   },
                   {
                    duration: 1000,
                    specialEasing: {
                      width: o.easingEffect
//                      height: 'easeOutBounce'
                    },
                    complete: function() {
                        o.resized = true;
                        infos.show('fast');
                    }
                   });

                }
            }
        };

        var delTab = {
            deleteTab: function(){
                o.delTrigger.live('click', function(e){
                    e.preventDefault();
                    var elem = $(this);
                    var profile = $(this).removeClass('deleteTab').attr('class');
                    $(this).addClass('deleteTab');
                    var url = o.baseUrl + o.url;

                    $( "#dialog:ui-dialog" ).dialog( "destroy" );
                    o.params = {
                        op: 'delete',
                        genericId: o.id,
                        profile: profile
                    };

                    o.confirmBox.dialog({
                        resizable: false,
                        modal: true,
                        width: 350,
                        buttons: {
                            Cancel: function() {
                                $( this ).dialog( "close" );

                            },
                            'Supprimer le profil': function() {
                                $( this ).dialog( "close" );
                                $.post(
                                    url,
                                    o.params,
                                    function(data){
                                        if (data)
                                        {
                                            delTab._removeTab(profile, elem);
                                        }
                                    },
                                    'json'
                                );
                            }
                        },
                        open: function(){
                            o.confirmBox.removeClass('hidden');
                        }
                    });
                });
            },
            _removeTab:function(profile, elem){
                //Find the index of the tab
                var index = main.getIndex(o.profilesTab.children('ul:first').children('li'), profile);
                //display the link in the dropDown to add again
                o.profilesLst.find('a[href=' + profile + ']').show();
                //Remove the tab
                o.profilesTab.tabs('remove', index);
            }
        };
        var main = {
            getIndex: function(parent, profile){
                var index = null;
                parent.each(function(i){
                    var hasClass = $(this).children('a').hasClass(profile);
                    if (hasClass)
                    {
                        index = i;
                        return false;
                    }
                });
                return index;
            },
            setValidate: function(action){
                $(':input, select').live('change keyup', function(event){
                    var btn = $(this).parents('form:first').find('#submitSave');
                    if (btn.attr('disabled'))
                        main.setEditedTabStyle(btn);
                });
//                $(':input, select').live('blur', function(event){
//                    main.logChanges($(this), event.type);
//
//                });
            },
//            logChanges: function(obj, event){
//                if (obj == undefined)
//                {
//                    obj = $(':input, select').live('focus', function(event){
//                        main.logChanges($(this), event.type);
//                    });
//
//                }
//                else
//                {
//                    var keyExists = false;
//                    var id = obj.attr('id');
//                    var val = obj.val();
//                    $.each(o.logValues, function(key, value){
//                    console.log(id, key, event, value);
//                        if (id == key && event != 'focusin')
//                        {
//                            if (val == value)
//                                o.logValues.nbChanges -= 1;
//                            else
//                                o.logValues.nbChanges += 1;
//
//                        }
//                        else if (event == 'focusin')
//                        {
//                            if (key == id)
//                                keyExists = true;
//                        }
//
//                    });
//                    if (!keyExists)
//                        o.logValues[id] = val;
//                }
//                console.log(o.logValues);
//            },
            changeTab: function(){
                o.profilesTab.tabs({
                    select: function(event, ui){
                        if (ui.index > 0)
                        {
                            var form = $(ui.panel).children('form');
                            var dataArea = form.find('#fieldset-actions').children('div');
                            var valStr = main._getInfos();
                            var dest = $();
                            var destExists = dataArea.find('ul').hasClass(o.infos.attr('class'));
                            if (!destExists)
                                dest = o.infos.clone();
                            else
                                dest = dataArea.find('ul:first');

                            dest.children('li').html(valStr);
                            dataArea.prepend(dest);
                            dest.fadeIn();
                        }
                    }
                });
            },
            _getInfos: function(){
                var formGeneral = o.profilesTab.find('#genericProfile');
                var formOrder = o.profilesTab.find('#orders');
                var gender  = formGeneral.find('#GP_Salutation option:selected').text();
                var fstName = formGeneral.find('#GP_FirstName').val();
                var lstName = formGeneral.find('#GP_LastName').val();
                var company = formOrder.find('#MP_CompanyName').val();
                var email   = formGeneral.find('#GP_Email').val();

                var dataStr = gender + o.blank + fstName + o.blank + lstName;
                dataStr += o.separator;
                if (company != undefined && company.length > 0)
                {
                    dataStr += company;
                    dataStr += o.separator;
                }

                dataStr += email;

                return dataStr;
            },
            setEditedTabStyle: function(elem){
                var currentTab = main._getCurrentTab(elem);
                var thisText = currentTab.text();
                elem.removeAttr('disabled');
                currentTab.text(thisText + ' *');
                currentTab.addClass('modified');
            },
            setSavedTabStyle: function(currentTab, button){
//                var currentTab = main._getCurrentTab(elem);
                var thisText = currentTab.text();
                button.attr('disabled', 'disabled');
                //Detect * and remove it
                var newText = thisText.replace("*","");
                // Set the new text
                currentTab.text(newText);
                currentTab.removeClass('modified');
            },
            _getCurrentTab: function(elem){
                var divParent = elem.parents('div[class^=ui-tabs-panel]:first');
                var id = divParent.attr('id');
                var container = divParent.parent();
                var currentTab = container.children('ul[class^=ui-tabs-nav]:first').find('a[href=#'+ id +']');

                return currentTab;
            },
            save: function(){

                o.saveBtn.live('click', function(e){
//                    e.preventDefault();
                    var button = $(this);
                    var form = $(this).parents('form:first');
                    var data = form.serialize();
                    var currentTab = main._getCurrentTab($(this));
                    var profile = currentTab.attr('id');
                    var url = o.baseUrl + '/users/index/' + profile + '/actionKey/edit/id/' + o.id + '?' + data;
                    o.params = {
                        data: data,
                        genericId: o.id,
                        profile: profile
                    };
                    form.validate({
                        highlight: function(element, errorClass) {
                            $(element).addClass(errorClass);
                        } ,
                        unhighlight: function(element, errorClass) {
                            $(element).removeClass(errorClass);
                        } ,
                        ignore: ":hidden,.hidden"
                    });
                    var fields = form.find("input, select");
                    fields.each(function (item) {
                        item = $(this);
                        if (item.attr('class').match(/Required/gi))
                            item.rules("add", {
                                required: true,
                                messages: {
                                    required: o.isEmpty
                                }
                            });
                    });
                    var isValid = form.valid();
                    if (isValid)
                    {
                        e.preventDefault();
                        $.post(
                            url,
                            o.params,
                            function(data){
                                if (data)
                                {
                                    main.setSavedTabStyle(currentTab, button);
                                    return false;
                                }
                            },
                            'json'
                        );
                    }
                });
            },
            _serialize: function(form){
                var data = form.serialize();
                var splitData = data.split('&');
                if (splitData.length < 1)
                    splitData = data;

                var obj = {};
                for (var i=0; i < splitData.length; i++)
                {
                    var tmp = splitData[i].split('=');
                    obj[tmp[0]] = tmp[1];
                }

                return obj;
            }
        }

        addTab.toggle();
        addTab.add();
        delTab.deleteTab();
//        main.logChanges();
        main.setValidate();
        main.changeTab();
        main.save();
    }

})(jQuery);