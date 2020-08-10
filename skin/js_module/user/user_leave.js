'use strict'; /** @type {!Array} */
$(document).ready(function() {
    
    var a = $('#xin_table').DataTable( {
        "ajax": site_url +'employee/leave/leave_list/'
    } );

$('#xin-form').submit(function(canCreateDiscussions) {
       console.log(canCreateDiscussions);

        canCreateDiscussions.preventDefault();
        var $realtime = $(this);
        var c = $realtime.attr('name');
        var showSliderNum = $('#remarks').code();

console.log($realtime);
console.log(c);
console.log(showSliderNum);
console.log(canCreateDiscussions.target.action);

        $('.save').prop('disabled', true);
//return false;
///* data $realtime[serialize]() + &is_ajax=1&add_type=leave&form= + c + &remarks= + showSliderNum
        $.ajax({
            type: 'POST',
            url: canCreateDiscussions.target.action,
            data: $realtime.serialize() + '&is_ajax=1&add_type=leave&form='+c,
            cache: false,
            success: function(theDirectoryEntry) {
                console.log(theDirectoryEntry);

                if (theDirectoryEntry.error != '') {
                    toastr.error(theDirectoryEntry.error);
                    $('.save').prop('disabled', false);
                } else {
                    a.ajax.reload(function() {
                        toastr.success(theDirectoryEntry.result);
                    }, true);
                    $('.add-form').fadeOut('slow');
                    $('#xin-form')[0].reset();
                    $('.save').prop('disabled', false);
                }
            }
        });
//*/
    });


} );

$(document).ready(function() {
    
    var ac = $('#xin_table').DataTable()({
        "bDestroy": true,
        "ajax": {
            'url': site_url + 'employee/leave/leave_list/',
         },
        "fnDrawCallback": function(oSettings) {
            //jQuery('[data-toggle="tooltip"]').tooltip();
        }
    });

    /*
    $([data-plugin="select_hrm"])[select2]($(this)[attr](data-options));
    $([data-plugin="select_hrm"])[select2]({
        width: 100%
    });
    $(#remarks)[summernote]({
        height: 70,
        minHeight: null,
        maxHeight: null,
        focus: false  n 
    });
    $(.note-children-container)[hide]();
    $(.date)[datepicker]({
        changeMonth: true,
        changeYear: true,
        dateFormat: yy-mm-dd,
        yearRange: (new Date)[getFullYear]() + : + ((new Date)[getFullYear]() + 10)
    });
    $(#xin-form)[submit](function(canCreateDiscussions) {
        canCreateDiscussions[preventDefault]();
        var $realtime = $(this);
        var c = $realtime[attr](name);
        var showSliderNum = $(#remarks)[code]();
        $(.save)[prop](disabled, true);
        $[ajax]({
            type: POST,
            url: canCreateDiscussions[target][action],
            data: $realtime[serialize]() + &is_ajax=1&add_type=leave&form= + c + &remarks= + showSliderNum,
            cache: false,
            success: function(theDirectoryEntry) {
                if (theDirectoryEntry[error] != ) {
                    toastr[error](theDirectoryEntry[error]);
                    $(.save)[prop](disabled, false);
                } else {
                    a[api]()[ajax][reload](function() {
                        toastr[success](theDirectoryEntry[result]);
                    }, true);
                    $(.add-form)[fadeOut](slow);
                    $(#xin-form)[0][reset]();
                    $(.save)[prop](disabled, false);
                }
            }
        });
    });
    */
});