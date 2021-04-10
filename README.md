**cntnd_schedule_results**

* includes in "php" files: `cInclude('module', 'includes/class.module.mparticleinclude.php');`
* includes in "includes" php files: `include_once($moduleHandler->getModulePath() . 'vendor/xyz.php');`

*contenido php functions*

* `$client = cRegistry::getClientId();`
* `$lang = cRegistry::getLanguageId();`  
* `mi18n("SELECT_ARTICLE")`
* `buildArticleSelect("CMS_VAR[2]", $oModule->cmsCatID, $oModule->cmsArtID);`

`$module = new cModuleHandler($cCurrentModule);
echo $module->getModulePath();`

*load js files in input_php*

`$.getScript("my_lovely_script.js", function() {
alert("Script loaded but not necessarily executed.");
});`