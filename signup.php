<?php
require_once("config.php");
require_once(ROOT_DIR . '/classes/Ajax.php');
?>
<!doctype html>
<html lang="en">
<head>
    <title>FRC Scout</title>
    <?php require_once('includes/meta.php') ?>
    <script src="<?php JS_URL ?>/js/jscolor.js"></script>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div id="demo-toast-example" class="mdl-js-snackbar mdl-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>

<script>
    var snackbarContainer = document.querySelector('#demo-toast-example');


    function showToast(message)
    {
        'use strict';
        var data = {message: message};
        snackbarContainer.MaterialSnackbar.showSnackbar(data);
    }
</script>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <main class="mdl-layout__content homescreen">
        <div class="mdl-layout__tab-panel is-active" id="overview">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">
                    <form method="post" action="" style="padding-top: 30px;" id="install-form">

                        <strong class="card-section-header">Account Details</strong>
                        <div class="mdl-card__supporting-text">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" name="username" id="username" placeholder=" ">
                                <label class="mdl-textfield__label" >Username</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="email" name="email" id="email" placeholder=" ">
                                <label class="mdl-textfield__label" >Email</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="password" name="password" id="password" placeholder=" ">
                                <label class="mdl-textfield__label" >Password</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="password" name="retypePassword" id="retypePassword" placeholder=" ">
                                <label class="mdl-textfield__label" >Retype Password</label>
                            </div>

                        </div>

                        <strong class="card-section-header">App Admin Account</strong>
                        <div class="mdl-card__supporting-text">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" name="adminFirstName" id="adminFirstName" placeholder=" ">
                                <label class="mdl-textfield__label" >First Name</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" name="adminLastName" id="adminLastName" placeholder=" ">
                                <label class="mdl-textfield__label" >Last Name</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" name="adminUsername" id="adminUsername" placeholder=" ">
                                <label class="mdl-textfield__label" >Username</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="password" name="adminPassword" id="adminPassword" placeholder=" ">
                                <label class="mdl-textfield__label" >Password</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="password" name="adminRetypePassword" id="adminRetypePassword" placeholder=" ">
                                <label class="mdl-textfield__label" >Retype Password</label>
                            </div>

                        </div>

                        <strong class="card-section-header">App Details</strong>
                        <div class="mdl-card__supporting-text">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input  autocomplete="new-password" required class="mdl-textfield__input" type="text" name="teamNumber" id="teamNumber" placeholder=" ">
                                <label class="mdl-textfield__label" >Team Number</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" id="appName" placeholder=" ">
                                <label class="mdl-textfield__label" >App Name</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required  class="jscolor mdl-textfield__input" type="text" id="primaryColor" value="03A9F4">
                                <label class="mdl-textfield__label" >Primary Color</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required  class="jscolor mdl-textfield__input" type="text" id="secondaryColor" value="0288D1">
                                <label class="mdl-textfield__label" >Secondary Color</label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
                                <input autocomplete="new-password" required  class="mdl-textfield__input" type="text" id="apiKey" placeholder=" ">
                                <label class="mdl-textfield__label" >API Key</label>
                            </div>
                            <button onclick="generateApiKey($('#apiKey'));" id="generate-api-key" type="button" class="default-mat-button mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                                Generate Key
                            </button>
                        </div>

                        <div class="mdl-card__supporting-text" style="margin-bottom: 30px;">
                            <button onclick="window.location.replace('<?php echo ROOT_URL ?>')" id="cancel" type="button" class="default-mat-button create-acc-button mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                                Cancel
                            </button>

                            <button name="save" id="save" type="submit" class="default-mat-button create-acc-button mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                                Save
                            </button>
                            <span hidden id="loading">
                                Creating account...
                                <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                            </span>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
</div>
<script defer>

    var accountBeingCreated = false;

    $(document).ready(function()
    {
        //get data from the ajax script
        $.post('/ajax/autocomplete.php',
            {
                action: 'load_team_list',
                number: $('#teamNumber').val()
            },
            function(data)
            {
                $( "#teamNumber" ).autocomplete({
                    source: JSON.parse(data),
                    select: function( event, ui )
                    {
                        event.preventDefault();
                        var selectedObj = ui.item;
                        $("#teamNumber").val(selectedObj.number);

                        if($('#appName').val() == "")
                            $("#appName").val(selectedObj.name + " Scouting");
                    }
                });

            });

        //prevent form from submitting, but keep validations
        $('#install-form').submit(function(e)
        {
            e.preventDefault();
            createAccount();
        });
    });

    /**
     * Generates an API key and populates it into the given field
     */
    function generateApiKey(field)
    {
        //get data from the ajax script
        $.post('/ajax/generate.php',
            {
                action: 'api_key'
            },
            function(data)
            {
                console.log(data);

                var parsedData = JSON.parse(data);

                console.log(parsedData['<?php echo Ajax::$RESPONSE_KEY ?>']);

                if(parsedData['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                    $(field).val(parsedData['<?php echo Ajax::$RESPONSE_KEY ?>']);

                else
                    showToast(parsedData['<?php echo Ajax::$RESPONSE_KEY ?>']);
            });
    }

    /**
     * Creates an account on the web server
     */
    function createAccount()
    {
        if(!accountBeingCreated)
        {
            $('#save').attr('disabled', 'disabled');
            $('#cancel').attr('disabled', 'disabled');
            $('#loading').removeAttr('hidden');

            accountBeingCreated = true;

            //get data from the ajax script
            $.post('/ajax/signup.php',
                {
                    action: 'create',
                    username : $('#username').val(),
                    email : $('#email').val(),
                    password : $('#password').val(),
                    retypePassword : $('#retypePassword').val(),
                    adminFirstName : $('#adminFirstName').val(),
                    adminLastName : $('#adminLastName').val(),
                    adminUsername : $('#adminUsername').val(),
                    adminPassword : $('#adminPassword').val(),
                    adminRetypePassword : $('#adminRetypePassword').val(),
                    teamNumber : $('#teamNumber').val(),
                    appName : $('#appName').val(),
                    apiKey : $('#apiKey').val(),
                    primaryColor : $('#primaryColor').val(),
                    secondaryColor : $('#secondaryColor').val()
                },
                function(data)
                {
                    var parsedData = JSON.parse(data);

                    if(parsedData['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                        window.location.replace("<?php echo ROOT_URL ?>?installSuccess=1");

                    else
                        showToast(parsedData['<?php echo Ajax::$RESPONSE_KEY ?>']);

                    $('#save').removeAttribute('disabled');
                    $('#cancel').removeAttribute('disabled');
                    $('#loading').attr('hidden', 'hidden');

                    accountBeingCreated = false;
                });
        }
    }

    //remove red from required fields
    MaterialTextfield.prototype.checkValidity = function ()
    {
        var CLASS_VALIDITY_INIT = "validity-init";
        if (this.input_ && this.input_.validity && this.input_.validity.valid) {
            this.element_.classList.remove(this.CssClasses_.IS_INVALID);
        } else {

            if (this.input_ && this.input_.value.length > 0) {
                this.element_.classList.add(this.CssClasses_.IS_INVALID);
            }
            else if(this.input_ && this.input_.value.length === 0)
            {
                if(this.input_.classList.contains(CLASS_VALIDITY_INIT))
                {
                    this.element_.classList.add(this.CssClasses_.IS_INVALID);
                }
            }


        }

        if(this.input_.length && !this.input_.classList.contains(CLASS_VALIDITY_INIT))
        {
            this.input_.classList.add(CLASS_VALIDITY_INIT);
        }
    };

</script>

<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>

