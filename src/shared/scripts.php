<!-- <script src="assets/lib/jquery/jquery.min.js"></script>
<script src="assets/lib/jquery/jquery.validate.min.js"></script>
<script src="assets/lib/jquery/additional-methods.min.js"></script>
<script src="assets/vendor/fomantic-ui/dist/semantic.min.js"></script>
<script src="assets/lib/DataTables/datatables.min.js"></script>
<script src="assets/lib/DataTables/dataTables.semanticui.min.js"></script>
<script src="assets/lib/DataTables/dataTables.responsive.min.js"></script>
<script src="assets/lib/DataTables/responsive.semanticui.min.js"></script>
<script src="assets/lib/lodash/lodash.min.js"></script>
<script src="assets/js/spinner.js"></script>
<script src="assets/js/darkmode.js"></script>
<script src="assets/js/scripts.js"></script> -->
<script>
    $(function () {
        const AppConfig = {
            AJAX_SETTINGS: {
                timeout: <?php echo Config::get('AJAX_TIMEOUT'); ?>,
                cache: <?php echo Config::get('AJAX_CACHE') ? 'true' : 'false'; ?>,
                retryAttempts: <?php echo Config::get('AJAX_RETRY_ATTEMPTS'); ?>,
                retryDelay: <?php echo Config::get('AJAX_RETRY_DELAY'); ?>
            }
        };

        // Example jQuery AJAX setup
        $.ajaxSetup({
            timeout: AppConfig.AJAX_SETTINGS.timeout,
            cache: AppConfig.AJAX_SETTINGS.cache,
            error: function (xhr, status, error) {
                if (status === 'timeout') {
                    // Handle timeout error
                    console.log('Request timed out');
                }
            }
        });
    });
</script>