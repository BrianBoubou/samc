<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1 class="my-3"><?php echo ucfirst($student->first_name) ?> <?php echo ucfirst($student->last_name) ?> - <strong><?php echo $student->total ?></strong> pangs</h1>
        </div>
        <div class="col-md-2 offset-md-2">
            <img class="float-right" src="https://cdn.local.epitech.eu/userprofil/profilview/<?php echo $student->first_name ?>.<?php echo $student->last_name ?>.jpg">
        </div>
    </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="checks-tab" data-toggle="tab" href="#checks" role="tab" aria-controls="checks" aria-selected="true">Check-in / out</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pangs-tab" data-toggle="tab" href="#pangs" role="tab" aria-controls="pangs" aria-selected="false">Pangs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="excuses-tab" data-toggle="tab" href="#excuses" role="tab" aria-controls="excuses" aria-selected="false">Excuses</a>
        </li>
    </ul>
    <div class="tab-content mb-5" id="myTabContent">
        <div class="tab-pane fade show active pt-3" id="checks" role="tabpanel" aria-labelledby="checks-tab">
            <table class="table table-sm table-striped dataTable">
                <thead>
                    <tr>
                        <th>Jour</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($days as $day) { ?>
                    <tr>
                        <td><?php echo $day->day ?></td>
                        <td><?php echo $day->arrived_at ?></td>
                        <td><?php echo $day->leaved_at ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Jour</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="tab-pane fade pt-3" id="pangs" role="tabpanel" aria-labelledby="pangs-tab">
            <table class="table table-sm table-striped dataTable">
                <thead>
                    <tr>
                        <th>Jour</th>
                        <th>Quantité</th>
                        <th>Raison</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($student->pangs as $pang) { ?>
                    <tr>
                        <?php  isset($pangs[4]) ? $pangs[4] : ''; ?>
                        <td><?php echo $pang[0] ?></td>
                        <td><?php echo $pang[1] ?></td>
                        <td><?php echo $pang[3] ?></td>
                        <?php if($auth['isAdmin'] === '1' && isset($pang[4])) { ?>
                            <td><a data-behavior='delete' href="<?php echo URL . 'students/deletePangs/' . $pang[4] ?>"><i class="fas fa-trash-alt"></i></td>
                        <?php } else { ?>
                            <td></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Jour</th>
                        <th>Quantité</th>
                        <th>Raison</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="tab-pane fade pt-3" id="excuses" role="tabpanel" aria-labelledby="excuses-tab">
            <table class="table table-sm table-striped dataTable">
                <thead>
                    <tr>
                        <th>Jour</th>
                        <th>Raison</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($days as $day) { ?>
                    <?php if($day->excused) { ?>
                    <tr>
                        <td><?php echo $day->day ?></td>
                        <td><?php echo $day->reason ?></td>
                        <?php if($auth['isAdmin'] === '1') { ?>
                        <td><a data-behavior='delete' href="<?php echo URL . 'students/deleteJustify/' . $day->id ?>"><i class="fas fa-trash-alt"></i></td>
                        <?php } else { ?>
                        <td></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Jour</th>
                        <th>Raison</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <canvas class="col-md-8 offset-md-2" id="pangsChart"></canvas>
    <canvas class="col-md-8 offset-md-2" id="attendanceChart"></canvas>
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
<script defer>
var table = $('.dataTable').DataTable({
    "pageLength": 10,
    "order" : [[0, "desc"]],
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
    }
});
// Charts Pangs
var ctx = $("#pangsChart");
var days = [];
var pangs = [];
<?php foreach($student->pangsHistory as $day => $pangs) { ?>
days.push("<?php echo $day ?>");
pangs.push("<?php echo $pangs ?>");
<?php } ?>
var myChart = new Chart (ctx, {
    type: 'line',
    data: {
        labels: days,
        datasets: [{
            label: 'Pangs',
            backgroundColor: 'red',
            borderColor: 'blue',
            data: pangs,
            fill: false
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 1000
                }
            }]
        }
    }
});

// Chart Attendance
var ctx2 = $("#attendanceChart");
var days2 = [];
var checkIn = [];
var checkOut = [];
<?php foreach ($student->attendanceHistory[0] as $day => $arrived_at) { ?>
    <?php if ($arrived_at !== null) { ?>
        days2.push("<?php echo $day ?>");
        checkIn.push(moment("1970-02-01 <?php echo $arrived_at ?>").valueOf());
    <?php } ?>
<?php } ?>
<?php foreach ($student->attendanceHistory[1] as $day => $leaved_at) { ?>
    <?php if ($leaved_at !== null) { ?>
        checkOut.push(moment("1970-02-01 <?php echo $leaved_at ?>").valueOf());
    <?php } elseif ($student->attendanceHistory[0][$day] !== null) { ?>
        checkOut.push(moment("1970-02-01 13:30:00").valueOf());
    <?php } ?>
<?php } ?>
var myChart2 = new Chart (ctx2, {
    type: "line",
    data: {
        labels: days2,
        datasets: [{
            label: "CheckIns",
            backgroundColor: "white",
            borderColor: "green",
            data: checkIn,
            fill: true
        }, {
            label: "checkOut",
            backgroundColor: "green",
            borderColor: "green",
            data: checkOut,
            fill: true
        }]
    },
    options: {
        scales: {
            yAxes: [{
                type: "linear",
                position: "left",
                ticks: {
                    min: moment('1970-02-01 08:00:00').valueOf(),
                    max: moment('1970-02-01 20:00:00').valueOf(),
                    stepSize: 3.6e+6,
                    beginAtZero: false,
                    callback: value => {
                        let date = moment(value);
                        if(date.diff(moment('1970-02-01 23:59:59'), 'minutes') === 0) {
                            return null;
                        }

                        return date.format('h:mm:ss a');
                    }
                }
            }]
        }
    }
});
</script>
<script>
$.ajaxSetup({
headers: {
}
});
$("[data-behavior='delete']").on('click', function (e) {
    e.preventDefault();

    swal({
        title: 'Confirmer',
        text: 'Etes-vous sur de vouloir supprimer cette entree ?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        }).then((result) => {
            if(result.value) {
                table
                .row( $(this).parents('tr') )
                .remove()
                .draw();
                $.ajax({
                    url: $(this).attr('href'),
                    method: "post",
                    success: function(){
                        swal(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        );
                    }
                })
            }
        });
});
</script>
