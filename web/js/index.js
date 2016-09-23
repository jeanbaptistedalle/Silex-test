/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    initDataTable('.dataTable');
    initTooltip();
    initEvent();
    showFlashbagsInToaster();
});

function initTooltip() {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({
            placement: 'bottom',
        });
    });
}

function initEvent() {
    $('body').on('click', '.btnEditerPersonnageAjax, .btnNouveauPersonnageAjax', function () {
        var data = {};
        var dataId = $(this).attr('data-id');
        if (dataId !== undefined) {
            data['id'] = dataId;
        }
        $ajax($(this), {
            type: 'POST',
            url: $(this).attr("data-url"),
            data: data,
            success: function (response) {
                loadCharacterModal(response);
            }});
    });

    $('body').on('submit', '.ajaxForm', function (e) {
        e.preventDefault();
        var that = $(this);
        var button = that.find('button[type="submit"]');
        $ajax(button, {
            type: that.attr('method'),
            url: that.attr('action'),
            data: that.serialize(),
            success: function (data) {
                if (data !== 'undefined') {
                    if (data['reload'] !== undefined && data['reload']) {
                        $('#fillableModal').modal('hide');
                        refreshDataTables();
                        showFlashbagsInToaster();
                    }
                    if (data['html'] !== undefined) {
                        that.find('.form_body').html(data['html']);
                    }
                }
            }}
        );
    });
}

function loadCharacterModal(response) {
    $('#fillableModal_body').html(response);
    $('#fillableModal_title').text($('#hdTitle').val());
    $('#fillableModal').modal('show');
}

function refreshDataTables() {
    $('.dataTable').each(function (idx, val) {
        $(val).find('tbody').html(getSpinner());
        $.ajax({
            url: $(val).attr('data-source-url'),
            type: 'POST',
            success: function (data) {
                if (data['html'] !== undefined) {
                    $(val).find('tbody').html(data['html']);
                    $('input[type="search"][aria-controls="{0}"]'.format($(val).attr('id'))).change();
                }
            },
        });
    });
}

function initDataTable(selector) {
    $(selector).DataTable({
        "language": {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        }
    });
}

function $ajax(button, options) {
    var oldHtml = button.html();
    $(button).css('width', $(button).outerWidth());
    button.html(getSpinner());
    button.attr('disabled', true);
    $.ajax(options).done(function () {
        button.html(oldHtml);
        button.attr('disabled', false);
    });
}

function getSpinner() {
    return '<i class="glyphicon glyphicon-refresh spinning"></i>';
}