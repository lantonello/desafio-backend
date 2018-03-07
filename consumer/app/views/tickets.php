<!-- Static navbar -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">NeoAssist Tickets</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="javascript:loadTickets();">Todos</a></li>
                <li><a href="javascript:loadTickets('order=DateCreate,asc');">Antigos primeiro</a></li>
                <li><a href="javascript:loadTickets('order=DateCreate,desc');">Recentes primeiro</a></li>
                <li><a href="javascript:loadTickets('order=Priority');">Prioridade Alta</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Tickets</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="tickets" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Criado em</th>
                                <th>Última interação</th>
                                <th>Prioridade</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <div id="page-links" class="btn-group" role="group"></div>
            </div>
        </div>
    </div>
</div>
<input id="api_url" type="hidden" value="<?php echo $api_url; ?>">
