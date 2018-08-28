<div class="container">
  <div class="row justify-content-md-center mt-5">
      <div class="col-md-8">
          <?php if (isset($_GET['errors-login-live'])) { ?>
          <div class="alert alert-warning" role="alert" id="alert-live">
              Aucun utilisateur n'est associé à ce mail
          </div>
        <?php } ?>
          <div class="jumbotron">
              <h1 class="display-4"><i class="fab fa-microsoft"></i> Connexion</h1>
              <p class="lead">Veuillez utiliser la connexion Office 365 (vos identifiants EPITECH) pour acceder aux details de vos pangs.</p>
              <a class="btn btn-primary btn-lg" href="<?php echo URL; ?>home/live" role="button"><i style="font-weight: normal;" class="fab fa-microsoft"></i> Connexion avec Office 365</a>
          </div>
          <button class="btn btn-primary my-3" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
              Connexion classique
          </button>
          <div class="collapse" id="collapse">
              <div class="card">
                  <div class="card-header">Seuls les administrateurs peuvent utiliser la connexion classique, les étudiants sont priés de bien vouloir utiliser la connexion via Office365</div>
                  <?php if (!empty($errors)) { ?>
                    <div class="invalid-feedback">
                        <strong>Une erreur est survenue veuillez vérifier votre saisie</strong>
                    </div>
                  <?php } ?>
                  <div class="card-body">
                      <form class="form-horizontal" method="POST" action="<?php echo URL; ?>home/login">

                          <div class="form-group row">
                              <label for="email" class="col-lg-4 col-form-label text-lg-right">E-Mail Address</label>

                              <div class="col-lg-6">
                                  <input
                                          id="email"
                                          type="email"
                                          class="form-control<?php  isset($errors['email']) ? ' is-invalid' : '' ?>"
                                          name="email"
                                          value=""
                                          required
                                          autofocus
                                  >
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="password" class="col-lg-4 col-form-label text-lg-right">Password</label>

                              <div class="col-lg-6">
                                  <input
                                          id="password"
                                          type="password"
                                          class="form-control<?php  isset($errors['password']) ? ' is-invalid' : '' ?>"
                                          name="password"
                                          required
                                  >
                              </div>
                          </div>

                          <div class="form-group row">
                              <div class="col-lg-8 offset-lg-4">
                                  <button type="submit" class="btn btn-primary">
                                      Login
                                  </button>

                              </div>
                          </div>
                      </form>
                  </div>
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
if (document.getElementById('alert-live'))
    setTimeout(function(){ document.getElementById('alert-live').remove() }, 4500);
if (document.getElementById('alert-disconnected'))
{
    $("#alert-disconnected").click(function(e) {
        $(this).remove();
    });
}
</script>
