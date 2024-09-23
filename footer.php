<script src="https://getbootstrap.com/docs/5.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script>https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js</script>
<script type="text/javascript">
$(document).ready(function() {
    $("#datatable").dataTable({
        "oLanguage": {
            "sLengthMenu": "Afficher MENU Enregistrements",
            "sSearch": "Rechercher:",
            "sInfo": "Total de TOTAL enregistrements (_END_ / _TOTAL_)",
            "oPaginate": {
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            }
        }
    })
});
</script>
<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <span class="text-muted"><i class="fa fa-copyright" aria-hidden="true"></i> Copyright 2024</span>
    </div>
</footer>

</body>

</html>