<div class="container">
    <h1 class="mt-3 mb-3">Ajouter un Administrateur</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <?php if (isset($_GET['error-email'])) { ?>
                <div class="alert alert-danger text-center" role="alert" id="alert-live">
                    J'ai dit un mail EPITECH ! Non mais oh !
                </div>
            <?php } ?>
            <?php if (isset($_GET['error-post-admin'])) { ?>
                <div class="alert alert-warning text-center" role="alert" id="alert-live">
                    Veuillez remplir l'email de l'administrateur que vous souhaitez ajouté.
                </div>
            <?php } ?>
            <div class="card">
                <div class="card-body">
                    <form role="form" method="post" action="<?php echo URL . 'admin/addPost' ?>">

                        <div class="form-group">
                            <div class="alert alert-primary" role="alert">
                                L'administrateur doit obligatoirement avoir une adresse mail epitech
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="text" class="form-control" name="email" required>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Ajouter un administrateur
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
<script>
if (document.getElementById('alert-live'))
    setTimeout(function(){ document.getElementById('alert-live').remove() }, 3000);
</script>
