?><?php
// cntnd_schedule_results_input

// input/vars
$filename = "CMS_VALUE[1]";
$separator = "CMS_VALUE[2]";
if (empty($separator)){
  $separator=',';
}
$files = array();

// includes
cInclude('module', 'includes/script.cntnd_schedule_results_input.php');

// data load
$db = new cDb;

$cfgClient = cRegistry::getClientConfig();
$uploadDir = $cfgClient[$client]['upl']['path'];

// medien, images, folders
$cfg = cRegistry::getConfig();

$sql = "SELECT * FROM :table WHERE idclient=:idclient AND filetype IN ('csv') ORDER BY dirname ASC, filename ASC";
$values = array(
    'table' => $cfg['tab']['upl'],
    'idclient' => cSecurity::toInteger($client)
);
$db->query($sql, $values);
while ($db->nextRecord()) {
    $files[$db->f('idupl')] = array('filename' => $db->f('dirname').$db->f('filename'), 'filepath' => $uploadDir.$db->f('dirname').$db->f('filename'));
}
?>
<div class="form-vertical">
    <div class="form-group">
        <div class="form-check form-check-inline">
            <input id="cntnd_schedule_results-activate_module" class="form-check-input" type="checkbox" name="CMS_VAR[3]" value="true" <?php if("CMS_VALUE[3]"=='true'){ echo 'checked'; } ?> />
            <label for="cntnd_schedule_results-activate_module" class="form-check-label"><?= mi18n("ACTIVATE_MODULE") ?></label>
        </div>
    </div>

    <div class="form-group">
        <div class="form-check form-check-inline">
            <input id="cntnd_schedule_results-simple" class="form-check-input" type="checkbox" name="CMS_VAR[4]" value="true" <?php if("CMS_VALUE[4]"=='true'){ echo 'checked'; } ?> />
            <label for="cntnd_schedule_results-simple" class="form-check-label"><?= mi18n("SIMPLE") ?></label>
        </div>
    </div>

    <div class="form-group">
        <label for="cntnd_schedule_results-vereinsnummer"><?= mi18n("VEREINSNUMMER") ?></label>
        <input id="cntnd_schedule_results-vereinsnummer" type="text" name="CMS_VAR[5]" value="CMS_VALUE[5]" />
    </div>

    <hr />

    <div class="form-group">
        <label for="cntnd_schedule_results-filename"><?= mi18n("LABEL_FILE") ?></label>
        <select name="CMS_VAR[1]" id="schedule_results-filename" size="1" onchange="this.form.submit()">
            <option value="false"><?= mi18n("SELECT_CHOOSE") ?></option>
            <?php
            foreach ($files as $value) {
                $selected='';
                if ($filename==$value['filepath']){
                    $selected='selected="selected"';
                }
                echo '<option value="'.$value['filepath'].'" '.$selected.'>'.$value['filename'].'</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="cntnd_schedule_results-separator"><?= mi18n("LABEL_SEPARATOR") ?></label>
        <input id="cntnd_schedule_results-separator" type="text" maxlength="1" name="CMS_VAR[2]" value="<?= $separator ?>"/>
    </div>
</div>
<?php
