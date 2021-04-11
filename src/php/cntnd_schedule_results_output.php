<?php
// cntnd_schedule_results_output

// assert framework initialization
defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

// editmode and more
$editmode = cRegistry::isBackendEditMode();

// input/vars
$filename = "CMS_VALUE[1]";
$separator = "CMS_VALUE[2]";
$moduleActive = "CMS_VALUE[3]";

// includes #1
cInclude('module', 'includes/class.cntnd_schedule_results.php');

// other/vars
$results = new CntndScheduleResults($filename, $separator);

// includes #2
if ($editmode){
  cInclude('module', 'includes/script.cntnd_schedule_results_output.php');

    if ($_POST){
        $stored = $results->store($_POST);
    }

    $data = $results->load();

	echo '<div class="content_box"><label class="content_type_label">'.mi18n("MODULE").'</label>';
    ?>
    <form id="cntnd_schedule_results" name="cntnd_schedule_results" method="post">
        <div id="spreadsheet"></div>
        <input type="hidden" name="cntnd_schedule_results-csv" id="cntnd_schedule_results-csv" />
        <input type="hidden" name="cntnd_schedule_results-headers" id="cntnd_schedule_results-headers" />
    </form>
    <script>
        $(document).ready(function(){
            var texts = {
                noRecordsFound:'<?= mi18n("noRecordsFound") ?>',
                showingPage:'<?= mi18n("showingPage") ?>',
                show:'<?= mi18n("show") ?>',
                entries:'<?= mi18n("entries") ?>',
                insertANewColumnBefore:'<?= mi18n("insertANewColumnBefore") ?>',
                insertANewColumnAfter:'<?= mi18n("insertANewColumnAfter") ?>',
                deleteSelectedColumns:'<?= mi18n("deleteSelectedColumns") ?>',
                renameThisColumn:'<?= mi18n("renameThisColumn") ?>',
                orderAscending:'<?= mi18n("orderAscending") ?>',
                orderDescending:'<?= mi18n("orderDescending") ?>',
                insertANewRowBefore:'<?= mi18n("insertANewRowBefore") ?>',
                insertANewRowAfter:'<?= mi18n("insertANewRowAfter") ?>',
                deleteSelectedRows:'<?= mi18n("deleteSelectedRows") ?>',
                editComments:'<?= mi18n("editComments") ?>',
                addComments:'<?= mi18n("addComments") ?>',
                comments:'<?= mi18n("comments") ?>',
                clearComments:'<?= mi18n("clearComments") ?>',
                copy:'<?= mi18n("copy") ?>',
                paste:'<?= mi18n("paste") ?>',
                saveAs:'<?= mi18n("saveAs") ?>',
                about: '<?= mi18n("about") ?>',
                areYouSureToDeleteTheSelectedRows:'<?= mi18n("areYouSureToDeleteTheSelectedRows") ?>',
                areYouSureToDeleteTheSelectedColumns:'<?= mi18n("areYouSureToDeleteTheSelectedColumns") ?>',
                thisActionWillDestroyAnyExistingMergedCellsAreYouSure:'<?= mi18n("thisActionWillDestroyAnyExistingMergedCellsAreYouSure") ?>',
                thisActionWillClearYourSearchResultsAreYouSure:'<?= mi18n("thisActionWillClearYourSearchResultsAreYouSure") ?>',
                thereIsAConflictWithAnotherMergedCell:'<?= mi18n("thereIsAConflictWithAnotherMergedCell") ?>',
                invalidMergeProperties:'<?= mi18n("invalidMergeProperties") ?>',
                cellAlreadyMerged:'<?= mi18n("cellAlreadyMerged") ?>',
                noCellsSelected:'<?= mi18n("noCellsSelected") ?>',
            };
            var data = [<?= $data['data'] ?>];
            var headers = <?= $data['headers'] ?>;
            var mySpreadsheet = jspreadsheet(document.getElementById('spreadsheet'), {
                data: data,
                columns: headers,
                defaultColWidth: 120,
                defaultColAlign: 'left',
                text: texts,
                columnSorting: false,
                toolbar:[{
                    type: 'i',
                    content: 'save',
                    onclick: function () {
                        $('#cntnd_schedule_results').submit();
                    }
                },{
                    type: 'i',
                    content: 'undo',
                    onclick: function() {
                        mySpreadsheet.undo();
                    }
                },{
                    type: 'i',
                    content: 'redo',
                    onclick: function() {
                        mySpreadsheet.redo();
                    }
                }]
            });
            $('#cntnd_schedule_results').submit(function() {
                var data = mySpreadsheet.getData();
                var headers = mySpreadsheet.getHeaders();
                $('#cntnd_schedule_results-csv').val(Base64.encode(JSON.stringify(data)));
                $('#cntnd_schedule_results-headers').val(Base64.encode(JSON.stringify(headers)));
                return true;
            });
        });
    </script>
<?php
}
else {
    $data = $results->data();

    $aktive = array();
    $junioren = array();
    $kifu = array();
    foreach ($data as $result){
        if ($result['Block']=="Aktive"){
            $aktive[]=$result;
        }
        else if ($result['Block']=="Junioren"){
            $junioren[]=$result;
        }
        else if ($result['Block']=="Kinderfussball"){
            $kifu[]=$result;
        }
    }

    // smarty
    $smarty = cSmartyFrontend::getInstance();
    $smarty->assign('aktive', $aktive);
    $smarty->assign('junioren', $junioren);
    $smarty->assign('kifu', $kifu);
    $smarty->assign('active', $moduleActive);
    $smarty->display('default.html');
}
?>
