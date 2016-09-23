/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * String Format
 * "{0} is dead, but {1} is alive! {0} {2}".format("ASP", "ASP.NET")
 * outputs
 * ASP is dead, but ASP.NET is alive! ASP {2}
 */
if (!String.prototype.format) {
    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function (match, number) {
            return typeof args[number] != 'undefined'
                    ? args[number]
                    : match
                    ;
        });
    };
}

function showFlashbagsInToaster() {
    $.ajax({
        url: $('#hdFlashBagUrl').val(),
        type: 'POST',
        success: function (data) {
            $.each(data, function (idx, flashBag) {
                toastr[flashBag['level']](flashBag['message']);
            });
        }
    });
}