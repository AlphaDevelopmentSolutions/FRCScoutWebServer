<div id="toast" class="mdl-js-snackbar mdl-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>

<dialog class="mdl-dialog" style="width: 500px;">
    <h3 class="mdl-dialog__title" style="font-size: 20px;" id="dialog-title"></h3>
    <div class="mdl-dialog__content">
        <p id="dialog-message"></p>
    </div>
    <div class="mdl-dialog__actions">
        <button id="dialog-confirm" type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent confirm">
            Delete
        </button>
        <button id="dialog-cancel" type="button" class="mdl-button">Cancel</button>
    </div>
</dialog>

<script>
    var snackbarContainer = document.querySelector('#toast');
    var dialog;

    function showToast(message)
    {
        'use strict';
        var data = {message: message};
        snackbarContainer.MaterialSnackbar.showSnackbar(data);
    }

    $(document).ready(function ()
    {
        dialog = document.querySelector('dialog');
        if (!dialog.showModal)
        {
            dialogPolyfill.registerDialog(dialog);
        }

        $('#dialog-cancel').click(function ()
        {
            $('#dialog-confirm').unbind('click');
            dialog.close();
        });
    });

    function setOnDialogConfirm(func)
    {
        $("#dialog-confirm")
            .unbind('click')
            .click(func);
    }

    function showDialog(title, message, func)
    {
        setOnDialogConfirm(func);
        $('#dialog-title').html(title);
        $('#dialog-message').html(message);
        dialog.showModal();
    }

</script>


