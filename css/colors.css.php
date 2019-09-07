<?php
    header("Content-type: text/css; charset: UTF-8");
    require_once('../config.php');

?>

:root
{
  --color-primary: #<?php echo PRIMARY_COLOR ?>;
  --color-primary-dark: #<?php echo PRIMARY_COLOR_DARK ?>;
}

/**
Primary Colors
 */
.mdl-color--primary
{
  background-color: var(--color-primary) !important;
}

.mdl-color--primary-dark
{
  background-color: var(--color-primary-dark) !important;
}

.mdl-button--accent.mdl-button--accent.mdl-button--raised
{
  background-color: var(--color-primary) !important;
}

/**
Link Colors
 */
.mdl-demo .mdl-card__actions a {
  color: var(--color-primary) !important;
}

/**
Stats Colors
 */
.pre-game
{
  background-color: #0527af;
}

.autonomous
{
  background-color: #FFD966;
}

.teleop
{
  background-color: #00FFFF;
}

.end-game
{
  background-color: #9400ff;
}

.post-game
{
  background-color: #e900ff;
}

.min
{
  background-color: #ffa74f;
}

.max
{
  background-color: #9FC5E8;
}

.avg
{
  background-color: #64FF62;
}


/**
Generic Status Colors
 */
.good
{
  color: #64FF62;
}

.bad
{
  color: #E67C73;
}

/**
Overwrites disabled gray color
  */
.mdl-textfield > input[disabled] + .mdl-textfield__label,
.mdl-textfield > textarea[disabled] + .mdl-textfield__label,
.mdl-textfield__label
{
  color: var(--color-primary-dark) !important;
}

/**
Match Alliance Background Colors
 */
.blue-alliance-bg
{
  background-color: #EEEEFF;
}

.red-alliance-bg
{
  background-color: #FFEEEE;
}