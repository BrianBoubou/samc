<?php

$logCategory = [
    "Check-in / out",
    "Edit Check-in / out",
    "Ajout d'excuse",
    "Suppression d'excuse",
    "Ajouter / Retirer des pangs",
    "Suppression d'ajout / retrait de pangs"
];

?>
<div class="container">
    <h1 class="my-3">Logs</h1>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive mt-3">
                <table class="table table-sm table-striped dataTable">
                    <thead>
                        <th>ID</th>
                        <th>User</th>
                        <th>Category</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php foreach($logs as $log) { ?>
                            <tr>
                                <td><?php echo $log->id ?></td>
                                <td><?php echo $log->user_id ?></td>
                                <td><?php echo $logCategory[$log->category_id - 1] ?></td>
                                <td><?php echo $log->action ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <th>ID</th>
                        <th>User</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tfoot>
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
