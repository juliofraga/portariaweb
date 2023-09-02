<link id="pagestyle" href="<?= URL ?>/public/css/material-dashboard.css?v=3.0.4" rel="stylesheet" />
<div class="login_page" >
    <form name="form_login_admin" id="form_login_admin" method="POST" action="<?= URL ?>/login/validaLogin/">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-12 mx-auto">
                <div class="card z-index-0 fadeIn3 fadeInBottom">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="shadow-primary border-radius-lg py-3 pe-1" style="background-color: black;">
                            <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Acessar</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group input-group-outline my-3">
                            <input type="text" class="form-control" id="login" name="login" required placeholder="Login">
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <input type="password" class="form-control" id="pass" name="pass" placeholder="Senha" required>
                        </div>
                        <div class="form-check form-switch d-flex align-items-center mb-3">
                            <input class="form-check-input" type="checkbox" name="keepConnected" id="keepConnected">
                            <label class="form-check-label mb-0 ms-3" for="rememberMe">Lembrar-me</label>
                        </div>
                        <?php 
                            if(isset($_SESSION["pw_rotina"]) and $_SESSION["pw_tipo"] == 'error'){
                                echo "<div class='alert alert-danger' role='alert'>";
                                echo "<center>".$_SESSION["pw_mensagem"]."</center>";
                                echo "</div>";
                            }
                        ?>
                        <div class="text-center">
                            <input class="w-100 btn btn-lg btn-warning" type="submit" name="btLogin" id="btLogin" value="Entrar">
                        </div>
                        <p class="mt-4 text-sm text-center">
                            
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php
    $_SESSION["pw_rotina"] = null;
    $_SESSION["pw_tipo"] = null;
?>