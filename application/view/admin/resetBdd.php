<div class="container">
    <h1 class="mt-3 mb-3">Réinitialiser la Bdd</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <?php if (isset($_GET['error-secret'])) { ?>
                <div class="alert alert-warning text-center" role="alert" id="alert-live">
                    Le mot de passe secret ne correspond pas.
                </div>
            <?php } ?>
            <div class="card">
                <div class="card-body">
                    <form role="form" method="post" action="<?php echo URL . 'admin/postResetBdd' ?>">

                        <div class="form-group">
                            <div class="alert alert-primary" role="alert">
                                Vous devez connaitre le mot de passe secret pour pouvoir effectuer cette opération
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="secret">Secret password</label>
                            <input id="secret" type="text" class="form-control" name="secret" required>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Confirmer
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
