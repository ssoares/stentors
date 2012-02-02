function assignPage(from_id, from_val, to_id, to_val){
    $('#' + from_id).val(from_val);
    $('#'+ to_id).val(to_val);
}

function openPagePicker(pickerId){
    $('#'+pickerId).slideDown('fast');
}

function closePagePicker(pickerId){
    $('#'+pickerId).slideUp('fast');
}

function openTypePanel(type){
    switch( type ){
        case 'page':
            $('.pageSelectionGroup').css('display','block');
            $('.externalLinkSelectionGroup').css('display','none');
            $('#ControllerName').val('');
            break;
        case 'external': 
            $('.pageSelectionGroup').css('display','none');
            $('.externalLinkSelectionGroup').css('display','block');
            $('#pagePicker').val('');
            $('#MenuLink').val('');
            break;
        case 'placeholder':
            $('.pageSelectionGroup').css('display','none');
            $('.externalLinkSelectionGroup').css('display','none');
            $('#pagePicker').val('');
            $('#MenuLink').val('');
            
    };
}