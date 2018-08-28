<div class="container">
    <h1 class="my-3">Administrateurs</h1>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive mt-3">
                <table class="table table-sm table-striped dataTable">
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user) { ?>
                            <tr>
                                <td><?php echo $user->id ?></td>
                                <td><?php echo $user->name ?></td>
                                <td><?php echo $user->email ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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
$('.dataTable').DataTable({
    "pageLength": 25,
    "order" : [[0, "desc"]],
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
    },
    "columnDefs": [
        {
            "targets": [ 0 ],
            "visible": false,
            "searchable": false
        }
    ]
});
</script>
<script>
if (document.getElementById('alert-live'))
    setTimeout(function(){ document.getElementById('alert-live').remove() }, 3000);
</script>
