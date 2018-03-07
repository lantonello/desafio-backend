<div class="row">
    <div class="col-md-4 col-md-offset-4 signin">
        <form method="POST" action="<?php echo $action; ?>" accept-charset="utf-8" onsubmit="return false;">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Por favor, identifique-se</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="username">Nome de Usuário</label>
                        <input type="email" id="username" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-primary " type="button" onclick="formPost(this, handleLoginResponse);">Entrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
