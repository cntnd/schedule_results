<style>
<?= file_get_contents($cfgClient[$client]["module"]["path"].'cntnd_schedule_results/css/cntnd_schedule_results.css') ?>
</style>

<script language="javascript" type="text/javascript">
$(document).ready(function() {
    $('#cntnd_schedule_results-simple').click(function(){
        if ($(this).is(':checked')) {
            $('#cntnd_schedule_results-vereinsnummer').disable();
        }
    });
});
</script>
