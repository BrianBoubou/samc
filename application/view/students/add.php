<div class="container">
    <h1 class="mt-3 mb-3">Ajouter un etudiant</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form role="form" method="post" action="<?php echo URL . 'students/addPost' ?>">

                        <div class="form-group">
                            <label for="f_name">Prenom</label>
                            <input id="f_name" type="text" class="form-control" name="firstname" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="l_name">Nom</label>
                            <input id="l_name" type="text" class="form-control" name="lastname" required>
                        </div>

                        <div class="form-group hidden" style="display: none;">
                            <label for="promotion">Promotion</label>
                            <select id="promotion" class="form-control" name="promotion" required>
                                <option disabled selected hidden>Choisir une promotion</option>
                                    <option value="1">Samsung Campus</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Ajouter un etudiant
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
