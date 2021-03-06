<div class="container">
    <h1 class="mt-3 mb-3">Ajouter / Retirer des pangs</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form role="form" method="post" action="<?php echo  URL . 'students/updatePangs'  ?>">

                        <div class="form-group">
                            <label for="students">Etudiant(s)</label>
                            <select id="students" class="form-control chosen" name="students[]" required multiple autofocus>
                                <?php foreach($students as $student) { ?>
                                    <?php if(isset($_GET['students']) && in_array($student->id, $_GET['students'])) { ?>
                                        <option selected value="<?php echo $student->id ?>"><?php echo  $student->first_name  ?> <?php echo  $student->last_name  ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $student->id ?>"><?php echo  $student->first_name  ?> <?php echo  $student->last_name  ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="day">Jour</label>
                            <input id="day" type="date" class="form-control" name="day" value="<?php echo  date('Y-m-d')  ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantité</label>
                            <input id="quantity" type="number" class="form-control" name="quantity">
                        </div>

                        <div class="form-group">
                            <label for="reason">Raison</label>
                            <input id="reason" type="text" class="form-control" name="reason">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Appliquer
                            </button>
                        </div>
                    </form>
                </div>
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
    $(".chosen").chosen();
</script>
