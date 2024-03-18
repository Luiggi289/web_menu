<?php ob_start(); ?>

    <div class="row">
        <div class="col-xs-12">
            <h2>INGRESA PLATO</h2>
            <form id="crud_platos_form_create" action="" method="POST">
                <input type="text" name="method" value="POST">
                <div class="form-group">
                    <input type="text" name="plato_name" class="form-control" placeholder="Nombre">
                </div>
                <div class="form-group">
                    <input type="text" name="plato_type" class="form-control" placeholder="Tipo">
                </div>
                <div class="form-group">
                    <input type="text" name="created_at" class="form-control" placeholder="FechaCreacion">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>

<?php $view_crud_platos  = ob_get_contents(); ?>

<?php ob_end_clean(); ?>