<div class="container">
    <h1 class="mt-3 mb-3">Edition de mot de passe</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <?php if (isset($_GET['errors'])) { ?>
                <div class="alert alert-warning" role="alert" id="alert-live">
                    Une erreur est survenue, veuillez vérifier votre saisie.
                </div>
            <?php } ?>
            <div class="card">
                <div class="card-body">
                    <form role="form" method="post" action="<?php echo URL . 'home/updatePassword' ?>">

                        <div class="form-group">
                            <label for="oldPassword">Mot de passe actuelle</label>
                            <input id="oldPassword" type="password" class="form-control" name="oldPassword" <?php if ($auth['HavePassword']) {?> required autofocus <?php } else { ?> disabled <?php } ?>>
                        </div>

                        <div class="form-group">
                            <label for="newPassword">Nouveau mot de passe</label>
                            <input id="newPassword" type="password" class="form-control" name="newPassword" required>
                        </div>

                        <div class="form-group">
                            <label for="newPasswordConfirm">Confirmer le nouveau mot de passe</label>
                            <input id="newPasswordConfirm" type="password" class="form-control" name="newPasswordConfirm" required>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Editer
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
    setTimeout(function(){ document.getElementById('alert-live').remove() }, 4500);
</script>
