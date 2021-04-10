?><?php
// cntnd_schedule_results_input

// input/vars
$orig_orderLeft   = "CMS_VALUE[10]";
$orig_orderRight  = "CMS_VALUE[11]";
$orderLeft        = html_entity_decode($orig_orderLeft,ENT_QUOTES);
$orderRight       = html_entity_decode($orig_orderRight,ENT_QUOTES);

// includes
cInclude('module', 'includes/script.cntnd_schedule_results_input.php');
cInclude('module', 'includes/class.cntndutil.php');

// classes
$conDb = new cDb;
$util = new CntndUtil();
$module = new cModuleHandler($cCurrentModule);
$absolutePath = $module->getModulePath();

// init all teams
$sql = "SELECT DISTINCT Team FROM spielplan ORDER BY Team";
$ret = $conDb->query($sql);
$scheduleTeams='';
while ($conDb->next_record()) {
    $scheduleTeams = $scheduleTeams.'{team:"'.$conDb->f('Team').'",side:"left"},';
}
// custom teams
$sql = "SELECT DISTINCT Team FROM spielplan_kifu ORDER BY Team";
$ret = $conDb->query($sql);
while ($conDb->next_record()) {
    $scheduleTeams = $scheduleTeams.'{team:"'.$conDb->f('Team').'",side:"left"},';
}
$scheduleTeams = '['.substr($scheduleTeams,0,-1).']';

$teamsLeftJson='[]';
if ($util->isJson($orderLeft)){
    $teamsLeftJson = $orderLeft;
}
$teamsRightJson='[]';
if ($util->isJson($orderRight)){
    $teamsRightJson = $orderRight;
}

// JS Vars
echo '<script language="javascript" type="text/javascript">';
echo 'var teamsLeftJson='.$teamsLeftJson.';'."\n";
echo 'var teamsRightJson='.$teamsRightJson.';'."\n";
echo 'var scheduleTeams = '.$scheduleTeams.';'."\n";
echo '</script>';

// CSS
$cssFiles = $module->getAllFilesFromDirectory('css');
$util->getAllCss($absolutePath, $cssFiles);
// JS
$jsFiles = $module->getAllFilesFromDirectory('js');
$util->getAllJs($absolutePath, $jsFiles);

?>
<div class="form-vertical">
    <div class="form-group">
        <div class="form-check form-check-inline">
            <input id="activate_module" class="form-check-input" type="checkbox" name="CMS_VAR[3]" value="true" <?php if("CMS_VALUE[3]"=='true'){ echo 'checked'; } ?> />
            <label for="activate_module" class="form-check-label"><?= mi18n("ACTIVATE_MODULE") ?></label>
        </div>
    </div>

    <div class="form-group">
        <label for="vereinsname"><?= mi18n("VEREINSNAME") ?></label>
        <input id="vereinsname" type="text" name="CMS_VAR[4]" value="CMS_VALUE[4]" />
    </div>

    <div class="form-group">
        <label for="vereinsnummer"><?= mi18n("VEREINSNUMMER") ?></label>
        <input id="vereinsnummer" type="text" name="CMS_VAR[5]" value="CMS_VALUE[5]" />
    </div>

    <button data-bind="click: eraseTeams" class="btn btn-light" type="submit"><?= mi18n("MODULE_RESET") ?></button>
</div>

<hr />

<div class="d-flex" style="width: 1000px;">
    <div class="w-33 config-container">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="newTeamText"><?= mi18n("NEW_TEAM") ?></label>
                    <input type="text" class="form-control form-control-sm" id="newTeamText" placeholder="<?= mi18n("NEW_TEAM") ?>" data-bind="value: newTeamText" />
                </div>
                <button data-bind="click: addTeam" class="btn btn-sm btn-primary"><?= mi18n("ADD") ?></button>
                <button data-bind="click: resetTeams" class="btn btn-sm"><?= mi18n("RESET") ?></button>
            </div>
        </div>

        <div data-bind="foreach: teamsLeft">
            <div class="card">
                <div class="card-body">
                    <strong>
                        Team <span data-bind="text: name"></span>
                        <!-- <span class="expand-button">expand</span> -->
                    </strong>
                    <div class="expand">
                        <div class="form-group">
                            <select data-bind="options: $root.availableTeams, value: team, optionsValue: 'team', optionsCaption: '<?= mi18n("CHOOSE_TEAM") ?>', optionsText: 'team'"></select>
                        </div>

                        <div class="form-group">
                            <label for="team"><?= mi18n("TEAM_NAME") ?></label>
                            <input type="text" class="form-control form-control-sm" id="team" placeholder="<?= mi18n("TEAM_NAME") ?>" data-bind="value: name"  />
                        </div>

                        <div class="form-group">
                            <label for="url"><?= mi18n("TEAM_URL") ?></label>
                            <input type="text" class="form-control form-control-sm" id="url" placeholder="<?= mi18n("TEAM_URL") ?>" data-bind="value: url" />
                        </div>
                        <div class="form-group">
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="firstTeam" value="true" data-bind="checked: firstTeam">
                                <label class="form-check-label" for="firstTeam"><?= mi18n("FIRST_TEAM") ?></label>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-light" data-bind="click: $parent.removeTeam"><?= mi18n("REMOVE") ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div data-bind="foreach: teamsRight">
            <div class="card">
                <div class="card-body">
                    <strong>
                        Team <span data-bind="text: name"></span>
                        <!-- <span class="expand-button">expand</span> -->
                    </strong>
                    <div class="expand">
                        <select class="form-control form-control-sm" data-bind="options: $root.availableTeams, value: team, optionsValue: 'team', optionsCaption: '-Bitte Team auswählen-', optionsText: 'team'"></select>
                        <div class="form-group">
                            <label for="team"><?= mi18n("TEAM_NAME") ?></label>
                            <input type="text" class="form-control form-control-sm" id="team" placeholder="<?= mi18n("TEAM_NAME") ?>" data-bind="value: name"  />
                        </div>
                        <div class="form-group">
                            <label for="url"><?= mi18n("TEAM_URL") ?></label>
                            <input type="text" class="form-control form-control-sm" id="url" placeholder="<?= mi18n("TEAM_URL") ?>" data-bind="value: url" />
                        </div>
                        <div class="form-group">
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customTeam" value="true" data-bind="checked: customTeam">
                                <label class="form-check-label" for="customTeam"><?= mi18n("CUSTOM_TEAM") ?></label>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-light" data-bind="click: $parent.removeTeam"><?= mi18n("REMOVE") ?></a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="w-33">
        <p class="col-title"><?= mi18n("BLOCK_ONE_TITLE") ?><p>
        <ul class="card sortable list-group" id="sortable-left" data-bind="sortable: { data: teamsLeft, afterMove: myDropCallback }">
            <li class="list-group-item">Team: <strong data-bind="text: name"></strong> <span data-bind="text: team"></span></li>
        </ul>
    </div>

    <div class="w-33">
        <p class="col-title"><?= mi18n("BLOCK_TWO_TITLE") ?><p>
        <ul class="card sortable list-group" id="sortable-right" data-bind="sortable: { data: teamsRight, afterMove: myDropCallback }">
            <li class="list-group-item">Team: <strong data-bind="text: name"></strong> <span data-bind="text: team"></span></li>
        </ul>
    </div>
</div>

<!-- data for Contenido -->
<input type="hidden" name="CMS_VAR[10]" id="orderLeft" value="<?php echo $orig_orderLeft; ?>" data-bind="value: $root.saveTeamsLeft()" />
<input type="hidden" name="CMS_VAR[11]" id="orderRight" value="<?php echo $orig_orderRight; ?>" data-bind="value: $root.saveTeamsRight()" />
<?php