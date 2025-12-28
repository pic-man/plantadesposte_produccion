<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.js"></script>
<!-- <script type="text/javascript" src="https://unpkg.com/xlsx@0.14.1/dist/xlsx.full.min.js"></script> -->
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/js/sweetalert2@11.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.datatables.js"></script>
<script>
    function cambiarRegistrosCambios() {
        var registrosCambios = $('#registrosCambios').prop('checked');
        
        $.ajax({
            type: "POST",
            url: "controlador/controlador.php",
            data: {
                cambiarRegistrosCambios: registrosCambios
            },
            dataType: "json",
            success: function (response) {
                if (response.status == "success") {
                    Swal.fire({
                        title: "Registros de cambios cambiado correctamente",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error al cambiar los registros de cambios",
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }
</script>