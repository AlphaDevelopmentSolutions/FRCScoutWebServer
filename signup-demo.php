<?php
$bypassCoreCheck = true;
require_once("config.php");
require_once(ROOT_DIR . '/classes/Ajax.php');
?>
<!doctype html>
<html lang="en">
<head>
    <title>FRC Scout</title>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
    <script src="<?php JS_URL ?>/js/jscolor.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <main class="mdl-layout__content homescreen">
        <div class="mdl-layout__tab-panel is-active" id="overview">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">
                    <div class="center-div-horizontal-outer">
                        <h2 class="center-div-horizontal-inner" id="demo-header">Demo Account</h2>
                        <div class="mdl-tooltip mdl-tooltip--large" for="demo-header">
                            Demo accounts add default values for demo purposes.<br><br>
                            <strong>
                                <i>Please Note</i><br>
                                Demo accounts and all of its content will be deleted 24 hours after creation.
                            </strong>
                        </div>
                    </div>
                    <form method="post" action="" style="padding-top: 30px;" id="install-form">

                        <strong class="card-section-header">Account Details</strong>
                        <div class="mdl-card__supporting-text">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" name="username" id="username" placeholder=" ">
                                <label class="mdl-textfield__label" >Username</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="username">
                                    Username that will be used to log into the landing page and main site.
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="email" name="email" id="email" placeholder=" ">
                                <label class="mdl-textfield__label" >Email</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="email">
                                    Contact email for the team.
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="password" name="password" id="password" placeholder=" ">
                                <label class="mdl-textfield__label" >Password</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="password">
                                    Password that will be used to log into the landing page and main site.
                                    <br>
                                    <br>
                                    Password must contain at least:
                                    <ul>
                                        <li>1 lower case letter</li>
                                        <li>1 upper case letter</li>
                                        <li>1 number</li>
                                        <li>8 characters</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="password" name="retypePassword" id="retypePassword" placeholder=" ">
                                <label class="mdl-textfield__label" >Retype Password</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="retypePassword">
                                    Password that will be used to log into the landing page and main site.
                                    <br>
                                    <br>
                                    Password must contain at least:
                                    <ul>
                                        <li>1 lower case letter</li>
                                        <li>1 upper case letter</li>
                                        <li>1 number</li>
                                        <li>8 characters</li>
                                    </ul>
                                </div>
                            </div>

                        </div>

                        <strong class="card-section-header">App Admin Account</strong>
                        <div class="mdl-card__supporting-text">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" name="adminFirstName" id="adminFirstName" placeholder=" ">
                                <label class="mdl-textfield__label" >First Name</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="adminFirstName">
                                    First name for your teams admin account.
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" name="adminLastName" id="adminLastName" placeholder=" ">
                                <label class="mdl-textfield__label" >Last Name</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="adminLastName">
                                    Last name for your teams admin account.
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" name="adminUsername" id="adminUsername" placeholder=" ">
                                <label class="mdl-textfield__label" >Username</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="adminUsername">
                                    Username for your teams page admin account.
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="password" name="adminPassword" id="adminPassword" placeholder=" ">
                                <label class="mdl-textfield__label" >Password</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="adminPassword">
                                    Password that will be used to log into the admin system on your teams page.
                                    <br>
                                    <br>
                                    Password must contain at least:
                                    <ul>
                                        <li>1 lower case letter</li>
                                        <li>1 upper case letter</li>
                                        <li>1 number</li>
                                        <li>8 characters</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="password" name="adminRetypePassword" id="adminRetypePassword" placeholder=" ">
                                <label class="mdl-textfield__label" >Retype Password</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="adminRetypePassword">
                                    Password that will be used to log into the admin system on your teams page.
                                    <br>
                                    <br>
                                    Password must contain at least:
                                    <ul>
                                        <li>1 lower case letter</li>
                                        <li>1 upper case letter</li>
                                        <li>1 number</li>
                                        <li>8 characters</li>
                                    </ul>
                                </div>
                            </div>

                        </div>

                        <strong class="card-section-header">App Details</strong>
                        <div class="mdl-card__supporting-text">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input  autocomplete="new-password" required class="mdl-textfield__input" type="text" name="teamNumber" id="teamNumber" placeholder=" ">
                                <label class="mdl-textfield__label" >Team Number</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="teamNumber">
                                    Enter your team number and select it from the list.
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required class="mdl-textfield__input" type="text" id="appName" placeholder=" ">
                                <label class="mdl-textfield__label" >App Name</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="appName">
                                    The name of your teams app.
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required  class="jscolor mdl-textfield__input" type="text" id="primaryColor" value="03A9F4">
                                <label class="mdl-textfield__label" >Primary Color</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="primaryColor">
                                    Primary color for the web and mobile application.
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input autocomplete="new-password" required  class="jscolor mdl-textfield__input" type="text" id="secondaryColor" value="0288D1">
                                <label class="mdl-textfield__label" >Secondary Color</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="secondaryColor">
                                    Darker primary color, usually a color accent, for the web and mobile application
                                </div>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
                                <input autocomplete="new-password" required  class="mdl-textfield__input" type="text" id="apiKey" placeholder=" ">
                                <label class="mdl-textfield__label" >API Key</label>
                                <div class="mdl-tooltip mdl-tooltip--large" for="apiKey">
                                    API key used for the mobile app to communicate and access data.
                                    <br>
                                    <br>
                                    If your would like one generated for you, click "Generate Key".
                                </div>
                            </div>
                            <button onclick="generateApiKey($('#apiKey'));" id="generate-api-key" type="button" class="default-mat-button mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                                Generate Key
                            </button>
                        </div>

                        <div class="mdl-card__supporting-text card-buttons" style="margin-bottom: 30px;">
                            <button onclick="window.location.replace('<?php echo ROOT_URL ?>')" type="button" class="default-mat-text-button mdl-button mdl-js-button mdl-js-ripple-effect">
                                <span class="button-text">Cancel</span>
                            </button>

                            <button name="save" id="save" type="submit" class="default-mat-button create-acc-button mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                                Save
                            </button>
                            <span hidden id="loading">
                                <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                                Creating account...
                            </span>
                            <div class="g-recaptcha" data-sitekey="6Lfx2bsUAAAAAOUWzZjeIs1X7ASb43js5-LKB3rp"></div>
                        </div>
                        <div class="center-div-horizontal-outer material-padding">
                            <div class="center-div-horizontal-inner">
                                By registering and logging in, you agree to our <a href="/privacy.html">Privacy Policy</a> and <a href="/terms.html">Terms of Use</a>.
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
<script defer>

    var accountBeingCreated = false;

    $(document).ready(function()
    {
        //get data from the ajax script
        $.post('<?php echo AJAX_URL ?>autocomplete.php',
            {
                action: 'team_list'
            },
            function(data)
            {
                var parsedData = JSON.parse(data);

                if(parsedData['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                {
                    $( "#teamNumber" ).autocomplete({
                        source: parsedData['<?php echo Ajax::$RESPONSE_KEY ?>'],
                        select: function( event, ui )
                        {
                            event.preventDefault();
                            var selectedObj = ui.item;
                            $("#teamNumber").val(selectedObj.number);

                            if($('#appName').val() == "")
                                $("#appName").val(selectedObj.name + " Scouting");
                        }
                    });
                }
            });

        //prevent form from submitting, but keep validations
        $('#install-form').submit(function(e)
        {
            e.preventDefault();

            if(grecaptcha.getResponse().length == 0)
                showToast("Please verify the CAPTCHA.");
            else
                createAccount();
        });
    });

    /**
     * Generates an API key and populates it into the given field
     */
    function generateApiKey(field)
    {
        //get data from the ajax script
        $.post('<?php echo AJAX_URL ?>generate.php',
            {
                action: 'api_key'
            },
            function(data)
            {
                var parsedData = JSON.parse(data);

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
            $.post('<?php echo AJAX_URL ?>account.php',
                {
                    action: 'create_core_demo',
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
                    secondaryColor : $('#secondaryColor').val(),
                    captchaKey: grecaptcha.getResponse()
                },
                function(data)
                {
                    grecaptcha.reset();

                    var parsedData = JSON.parse(data);

                    if(parsedData['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                        window.location.replace("<?php echo ROOT_URL ?>?installSuccess=1");

                    else
                        showToast(parsedData['<?php echo Ajax::$RESPONSE_KEY ?>']);

                    $('#save').removeAttr('disabled');
                    $('#cancel').removeAttr('disabled');
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
</body>
</html>

