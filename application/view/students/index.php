<div class="container">
    <?php if (isset($_GET['confirm-edit-password'])) { ?>
        <div class="alert alert-success" role="alert" id="alert-live">
            La modification du mot de passe à bien été prise en compte.
        </div>
    <?php } ?>
    <h1 class="my-3">Liste des etudiants</h1>
    <div class="row">
        <div class="col-12">
            <div class="row sticker">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sur la selection ...
                    </button>
                    <button id="help" class="btn btn-primary ml-4"><i class="fas fa-question-circle"></i> Afficher raccourcis clavier</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item action" id="editChecks" href="#"><i class="pr-2 fas fa-user-clock"></i> Editer Check-in / out</a>
                        <a class="dropdown-item action" id="editPangs" href="#"><i class="pr-2 far fa-chart-bar"></i> Ajouter / retirer pangs</a>
                        <a class="dropdown-item action" id="justify" href="#"><i class="pr-2 fas fa-file-medical"></i> Ajouter une excuse</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table id="ajaxStudents" class="table table-sm table-striped dataTable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Pangs</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th><i class="far fa-check-square"></i></th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Pangs</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?php foreach ($students as $student) { ?>
<div class="tooltip_templates" style="display: none;">
    <span id="image-<?php echo $student->id; ?>">
        <img src="https://cdn.local.epitech.eu/userprofil/profilview/<?php echo $student->first_name; ?>.<?php echo $student->last_name; ?>.jpg" />
    </span>
</div>
<?php } ?>



<!-- our JavaScript -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="<?php echo URL; ?>js/app.js"></script>
<script src="https://unpkg.com/sweetalert2@7.24.1/dist/sweetalert2.all.js"></script>
<script src="<?php echo URL; ?>js/jquery.sticky.js"></script>
<script type="text/javascript" src="<?php echo URL; ?>js/tooltipster.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/sl-1.2.6/datatables.min.js"></script>
<script type="text/javascript"  src="<?php echo URL; ?>js/chosen.jquery.min.js"></script>
<script src="<?php echo URL; ?>js/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script>
    $(".chosen").chosen();
</script>
<script src="<?php echo URL; ?>js/application.js"></script>
<script src="<?php echo URL; ?>js/ajaxCheckIn.js"></script>
<script>
$('#help').on('click', function(){
    swal({
    type: 'info',
    title: 'Aide',
    html:'<ul><li class="pb-1"><kbd>Ctrl + clic</kbd> pour selectionner plusieurs entrees</li><li><kbd>Maj + clic</kbd> pour selectionner toutes les lignes entre deux entrees</li></ul>',
    })
});
$(".sticker").sticky({topSpacing:20, zIndex: 99});
$("#checkAll").click(function (){
    $('input:checkbox').prop('checked', this.checked);
});
var table = $('#ajaxStudents')
.on( 'init.dt', function () {
    table.$('.image-tooltip').tooltipster({
        delay: 0,
        contentCloning: true,
        theme: 'tooltipster-shadow',
        side: 'left'
    });
} )
.DataTable({
    ajax: "<?php echo URL; ?>students/jsonStudentsData",
    select: {
    style:    'os',
    selector: 'td:not(:nth-last-child(-n+2))'
    },
    rowId: 'id',
    "order": [[1, "asc"]],
    "pageLength": 50,
    "columns": [
        { data: "id", visible: false },
        { data: "first_name" },
        { data: "last_name" },
        { data: "pangs" },
        { data: "checkin", searchable: false },
        { data: "checkout", searchable: false },
    ],
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
    }
});
var refresh;
var checked = 0;
function ajaxRefresh()
{
    checked = $(":checkbox:checked").length;
    if(checked === 0)
    {
        $("#alert-refresh").hide();
        table.ajax.reload(function (){
            table.$('.image-tooltip').tooltipster({
                delay: 0,
                contentCloning: true,
                theme: 'tooltipster-shadow',
                side: 'left'
            });
        });
    }
    else
    {
        $("#alert-refresh").show();
    }
}
setInterval(ajaxRefresh, 5000);

</script>
<script>
if (document.getElementById('alert-live'))
    setTimeout(function(){ document.getElementById('alert-live').remove() }, 4500);
</script>
