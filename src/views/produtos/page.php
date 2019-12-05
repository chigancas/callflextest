<main class="container-fluid d-flex flex-column flex-lg-row h-100 no-gutters p-0 w-100">

        <aside class="border-right col-12 col-lg-2 flex-shrink-1">

            <nav class="navbar p-0 h-100 flex-column navbar-expand-lg navbar-light bg-white">

                <a class="border-lg-bottom border-bottom-0 align-items-center d-flex justify-content-center m-0 navbar-brand px-4" href="../../dashboard">
                    <img src="/callflex/public/images/logo_callflex.png" class="img-fluid" alt="Callflex">
                </a>
    
                <button class="my-2 navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            
                <div class="collapse w-100 flex-fill px-3 pt-4 flex-column navbar-collapse" id="navbar">
                    <ul class="navbar-nav flex-column mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../../dashboard"><i class="fas fa-th-large mr-3"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../cadastro"><i class="fas fa-plus-square mr-3"></i> Cadastro</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="../../produtos"><i class="fas fa-th-list mr-3"></i> Produtos</a>
                    </li>
                    </ul>
                </div>
            </nav>

        </aside>

        <section class="align-items-center col-12 col-lg-10 content-section d-flex flex-column h-100 justify-content-center">

            <header class="align-items-center border-lg-bottom border-bottom-0   col-12 d-flex flex-column-reverse flex-lg-row header-pages justify-content-between mt-3 mt-lg-0 px-4">

            <section>

                <h4 class="m-0">Produtos</h4>

            </section>

            <section class="d-flex justify-content-center align-items-center ">

                <picture class="profile-pic">

                    <img src="/callflex/public/images/Blank-avatar.png" class="img-fluid" alt="Usuário">

                </picture>

                <h6 class="m-0 px-3">Admin</h6>

                <i class="fas fa-chevron-down cursor-pointer"></i>

            </section>

        </header>

        <section class="w-100 px-4 mt-4 alert-section">

            <div class="alert alert-success w-100 d-none" role="alert">
                A simple success alert—check it out!
            </div>

        </section>

        <section class="col-12 p-0 m-3 bg-white content-panel">

            <header class="col-12 py-3 d-flex flex-lg-row flex-column justify-content-between align-items-center">

                <h6 class="m-0">Produtos</h6>

                <section class="d-flex flex-lg-row flex-column justify-content-center align-items-center">

                    <div class="form-group m-lg-0 mr-lg-3 my-3 search-tool">
                        <div class="input-group">
                            <div class="input-group-prepend d-flex justify-content-center align-items-center">
                                <i class="fas fa-search"></i>
                            </div>
                            <input type="search" class="form-control" id="txt-search" placeholder="Pesquisar...">
                        </div>
                    </div>

                    <a href="../../cadastro" class="btn btn-dark"><i class="fas fa-plus mr-2"></i> Adicionar Novo</a>
                </section>


            </header>

            <section class="col-12 p-0 d-flex flex-column justify-content-start align-items-center">

                <div class="table-responsive">
                    <table class="table table-hover table-products">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Código</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        
                        $url = (int)explode('/', substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1))[3];
                        
                        foreach ($data['produtos']->result as $produto) { ?>
                            <tr>
                                <th scope="row"><?= $produto->id_product; ?></th>
                                <td><?= $produto->description; ?></td>
                                <td><?= $produto->code; ?></td>
                                <td>
                                    <div class="align-items-center d-flex justify-content-around py-1">
                                        <i class="far fa-edit mx-2 cursor-pointer"></i>

                                        <i class="far fa-eye mx-2 cursor-pointer"></i>

                                        <i class="far fa-trash-alt mx-2 cursor-pointer"></i>

                                        <i class="fas fa-toggle-on mx-2 cursor-pointer"></i>
                                    </div>
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>

                <div class="btn-toolbar btn-pages" role="toolbar" class="align-self-end">
                    <div class="btn-group" role="group">
                        <a href="<?= ($url === 1 ? $url : $url - 1 ) ?>"
                            class="btn btn-outline-secondary<?= ($url === 1 ? " disabled" : "" ) ?>"><i
                                class="fas fa-chevron-left"></i></a>
                        <a href="1" class="btn btn-outline-secondary<?= ($url == "1" ? " active" : "") ?>">1</a>
                        <?php 
                            $count = 0;
                            if ($data['total']->total > 10 ) {
                                $count = round($data['total']->total / 10);
                                for($i=2;$i < $count + 1;$i++) {
                        ?>
                        <a href="<?= $i ?>"
                            class="btn btn-outline-secondary<?= ($url == "$i" ? " active" : "") ?>"><?= $i ?></a>
                        <?php
                                }
                            }   
                        ?>
                        <a href="<?= ($count === $url ? $url : $url + 1 ) ?>"
                            class="btn btn-outline-secondary<?= ($count == $url ? " disabled" : "") ?>"><i
                                class="fas fa-chevron-right"></i></a>
                    </div>
                </div>

            </section>

        </section>

    </section>

</main>

<div class="modal fade modal-exclude" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-del" data-dismiss="modal">Excluir</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form class="d-flex flex-column" id="cad_product">

                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="text" id="txt-desc" class="form-control" placeholder="Descrição">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="text" id="txt-cod" class="form-control" placeholder="Código">
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-save" data-dismiss="modal">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-conf" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Produto excluído com sucesso</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>