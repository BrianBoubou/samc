<div class="container">
    <h1 class="mt-3 mb-3">Ajouter plusieurs etudiants</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <?php if (isset($_GET['error-file-type'])) { ?>
                <div class="alert alert-warning text-center" role="alert" id="alert-live">
                    Le fichier uploadé n'est pas un fichier CSV.
                </div>
            <?php } ?>
            <?php if (isset($_GET['error-header-csv'])) { ?>
                <div class="alert alert-warning text-center" role="alert" id="alert-live">
                    L'entete du fichier csv doit ce nommer login ou Login.
                </div>
            <?php } ?>
            <?php if (isset($_GET['error-upload-file'])) { ?>
                <div class="alert alert-danger text-center" role="alert" id="alert-live">
                    Erreur lors de l'upload du fichier vers le serveur, veuillez réessayer.
                </div>
            <?php } ?>
            <?php if (isset($_GET['error-empty-input'])) { ?>
                <div class="alert alert-danger text-center" role="alert" id="alert-live">
                    Veuillez remplir l'input comme indiquez plus bas ou bien selectionner un fichier csv.
                </div>
            <?php } ?>
            <div class="card">
                <div class="card-body">
                    <form enctype="multipart/form-data" role="form" method="post" action="<?php echo URL . 'students/storeBulk' ?>">

                        <div class="form-group">
                            <div class="alert alert-primary" role="alert">
                                Ajoutez les etudiants sous la forme <code>nom prenom</code> suivi d'un retour a la ligne.
                            </div>
                        </div>

                        <div class="form-group">
                            <textarea id="names" rows="5" type="text" class="form-control" name="names" autofocus></textarea>
                        </div>

                        <div class="form-group">
                            <div class="alert alert-primary" role="alert">
                                Ou importer depuis un fichier csv <code>( Entete : login, séparateur : ';' )</code>, le fichier doit contenir le mail de chacun des étudiants.
                            </div>
                        </div>

                        <div class="form-group">
                            <input name="file" type="file" />
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
                                Ajouter les etudiants
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
