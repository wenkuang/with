<!-- Load JS here for greater good =============================-->

<script src="flat_ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="flat_ui/js/jquery.ui.touch-punch.min.js"></script>

<script src="flat_ui/js/bootstrap-select.js"></script>
<script src="flat_ui/js/bootstrap-switch.js"></script>
<script src="flat_ui/js/flatui-checkbox.js"></script>
<script src="flat_ui/js/flatui-radio.js"></script>
<script src="flat_ui/js/jquery.tagsinput.js"></script>
<script src="flat_ui/js/jquery.placeholder.js"></script>
<script src="flat_ui/js/application.js"></script>

<script>


    $('.datetimepicker').datetimepicker();
    $(document).on("click", ".day,.month ", function() {
        $(".datepicker").hide();
    })

</script>
</body>
</html>
