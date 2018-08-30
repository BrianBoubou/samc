<div class="container">
    <div class="row">
        <div class="col-md-8 <?php if($student->hors_parcours == '1'){ echo "alert alert-danger"; } ?>">
            <h1 class="my-3"><?php echo ucfirst($student->first_name) ?> <?php echo ucfirst($student->last_name); ?> - <strong><?php echo $student->total ?></strong> pangs</h1>
            <?php if(abs($student->total) <= 0 && $student->hors_parcours != '1') { ?>
            <button id="removeStudent" class="btn btn-danger ml-4"><i class="fas fa-user-times" style="margin-right: 10px;"></i>Hors parcours</button>
            <?php } ?>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($days as $day) { ?>
                    <tr>
                        <td><?php echo $day->day ?></td>
                        <td id="checkIn-<?php echo $day->id ?>"><?php echo $day->arrived_at ?></td>
                        <td id="checkOut-<?php echo $day->id ?>"><?php echo $day->leaved_at ?></td>
                        <?php if($auth['isAdmin'] === '1') { ?>
                            <td><i data-checkin="<?php echo $day->arrived_at ?>" data-checkout="<?php echo $day->leaved_at ?>" data-day="<?php echo $day->day ?>" data-id="<?php echo $day->id ?>" class="fas fa-edit editChecks" style="cursor: pointer; margin-right: 8px;"></i></td>
                        <?php } else { ?>
                            <td></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Jour</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                        <th>Actions</th>
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
                        <td <?php  if(isset($pang[4])) { ?> id="pangdiff-<?php echo $pang[4] ?>" <?php } ?> ><?php echo $pang[1] ?></td>
                        <td <?php  if(isset($pang[4])) { ?> id="pangreason-<?php echo $pang[4] ?>" <?php } ?>><?php echo $pang[3] ?></td>
                        <?php if($auth['isAdmin'] === '1' && isset($pang[4])) { ?>
                            <td><i data-reason="<?php echo $pang[3] ?>" data-diff="<?php echo $pang[1] ?>" data-id="<?php echo $pang[4] ?>" class="fas fa-edit editPangDiff" style="cursor: pointer; margin-right: 8px;"></i><a data-behavior='delete' href="<?php echo URL . 'students/deletePangs/' . $pang[4] ?>"><i style="margin-left: 8px; margin-right: 8px;" class="fas fa-trash-alt"></i></td>
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
                        <td id="reason-<?php echo $day->id ?>"><?php echo $day->reason ?></td>
                        <?php if($auth['isAdmin'] === '1') { ?>
                        <td><i data-day="<?php echo $day->day; ?>" data-value="<?php echo $day->reason ?>" data-id="<?php echo $day->id ?>" class="fas fa-edit editExcuse" style="cursor: pointer; margin-right: 8px;"></i><a data-behavior='delete' href="<?php echo URL . 'students/deleteJustify/' . $day->id ?>" style="margin-left: 8px; margin-right: 8px;"><i class="fas fa-trash-alt"></i></td>
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
            backgroundColor: "#006060",
            borderColor: "white",
            data: checkIn,
            fill: true
        }, {
            label: "checkOut",
            backgroundColor: "green",
            borderColor: "silver",
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
$("#removeStudent").on('click', function (e) {
    e.preventDefault();

    swal({
        title: 'Hors parcours',
        text: 'Etes-vous sur de vouloir de vouloir définir cette étudiant comme étant hors parcours ?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    url: "<?php echo URL . 'students/ajaxHorsParcours/' . $student->id; ?>",
                    method: "post",
                    success: function(data){
                        window.location = "<?php echo URL ?>students";
                    }
                })
            }
        });
});

$(".editChecks").click(function(e) {
    var id = $(this).data("id");
    var day = $(this).data("day");
    if ($(this).hasClass("fa-edit"))
    {
        $(this).removeClass("fa-edit");
        $(this).addClass("fa-check-square");
        var checkIn = $(this).data("checkin");
        var checkOut = $(this).data("checkout");
        console.log(checkIn, checkOut);
        $("#checkIn-" + id).html("<input id=\"newCheckInValue" + id + "\" type=\"time\" value=\"" + checkIn + "\" style=\"width:96%; padding:0 2%;\" />");
        $("#checkOut-" + id).html("<input id=\"newCheckOutValue" + id + "\" type=\"time\" value=\"" + checkOut + "\" style=\"width:96%; padding:0 2%;\" />");
    }
    else {
        var newCheckInValue = $("#newCheckInValue" + id).val();
        var newCheckOutValue = $("#newCheckOutValue" + id).val();
        var that = $(this);
        $.ajax({
            type: "GET",
            url:  url + "students/ajaxUpdateChecks?id=" + id + "&checkIn=" + newCheckInValue + "&checkOut=" + newCheckOutValue + "&student=<?php echo $student->first_name . '.' . $student->last_name; ?>&day=" + day + "&studentId=<?php echo $student->id; ?>",
            success: function (data) {
                if (data == true)
                {
                    that.data("checkin", newCheckInValue);
                    that.data("checkout", newCheckOutValue);
                    that.removeClass("fa-check-square");
                    that.addClass("fa-edit");
                    $("#newCheckInValue" + id).remove();
                    $("#newCheckOutValue" + id).remove();
                    $("#checkIn-" + id).text(newCheckInValue);
                    $("#checkOut-" + id).text(newCheckOutValue);
                }
                else {
                    console.log("Ajax request error after calling : ajaxUpdateChecks method into student controller");
                }
            }
        });
    }
});

$(".editExcuse").click(function(e) {
    var id = $(this).data("id");
    var day = $(this).data("day");
    if ($(this).hasClass("fa-edit"))
    {
        $(this).removeClass("fa-edit");
        $(this).addClass("fa-check-square");
        var value = $(this).data("value");
        $("#reason-" + id).html("<input id=\"newValue" + id + "\" type=\"text\" value=\"" + value + "\" style=\"width:96%; padding:0 2%;\" />");
    }
    else {
        var newValue = $("#newValue" + id).val();
        var that = $(this);
        $.ajax({
            type: "GET",
            url:  url + "students/ajaxUpdateExcuse?id=" + id + "&reason=" + newValue + "&student=<?php echo $student->first_name . '.' . $student->last_name; ?>&day=" + day,
            success: function (data) {
                if (data == true)
                {
                    that.data("value", newValue);
                    that.removeClass("fa-check-square");
                    that.addClass("fa-edit");
                    $("#newValue" + id).remove();
                    $("#reason-" + id).text(newValue);
                }
                else {
                    console.log("Ajax request error after calling : ajaxUpdateExcuse method into student controller");
                }
            }
        });
    }
});

$(".editPangDiff").click(function(e) {
    var id = $(this).data("id");
    var reason = $(this).data("reason");
    var diff = $(this).data("diff");
    if ($(this).hasClass("fa-edit"))
    {
        $(this).removeClass("fa-edit");
        $(this).addClass("fa-check-square");
        $("#pangreason-" + id).html("<input id=\"newReasonValue" + id + "\" type=\"text\" value=\"" + reason + "\" style=\"width:96%; padding:0 2%;\" />");
        $("#pangdiff-" + id).html("<input id=\"newDiffValue" + id + "\" type=\"text\" value=\"" + diff + "\" style=\"width:96%; padding:0 2%;\" />");
    }
    else {
        var newReasonValue = $("#newReasonValue" + id).val();
        var newDiffValue = $("#newDiffValue" + id).val();
        var that = $(this);
        $.ajax({
            type: "GET",
            url:  url + "students/ajaxUpdateEditPang?id=" + id + "&reason=" + newReasonValue + "&diff=" + newDiffValue + "&student=<?php echo $student->first_name . '.' . $student->last_name; ?>",
            success: function (data) {
                if (data == true)
                {
                    that.data("reason", newReasonValue);
                    that.data("diff", newDiffValue);
                    that.removeClass("fa-check-square");
                    that.addClass("fa-edit");
                    $("#newReasonValue" + id).remove();
                    $("#newDiffValue" + id).remove();
                    $("#pangreason-" + id).text(newReasonValue);
                    $("#pangdiff-" + id).text(newDiffValue);
                }
                else {
                    console.log("Ajax request error after calling : ajaxUpdateEditPang method into student controller");
                }
            }
        });
    }
});
</script>
