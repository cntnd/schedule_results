<?php
// cntnd_schedule_results_output

// assert framework initialization
defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

// input/vars
$moduleActive     = "CMS_VALUE[3]";
$vereinsname      = "CMS_VALUE[4]";
$vereinsnummer    = "CMS_VALUE[5]";

$blockOne         = "CMS_VALUE[10]";
$blockTwo         = "CMS_VALUE[11]";

// includes
cInclude('module', 'includes/class.cntnd_schedule.php');

// other/vars
$schedule = new CntndSchedule($vereinsname, $vereinsnummer, $blockOne, $blockTwo);

// laden der daten
$spieleLeft = $schedule->blockOne();
$spieleRight = $schedule->blockTwo();

// smarty
$smarty = cSmartyFrontend::getInstance();
$smarty->assign('spieleLeft', $spieleLeft);
$smarty->assign('spieleRight', $spieleRight);
$smarty->assign('active', $moduleActive);
$smarty->display('default.html');
?>