<?php
include __DIR__.DIRECTORY_SEPARATOR."Includes/header.php";
?>
<body class="sidebar-fixed sidebar-dark header-light header-fixed" id="body">
<div class="mobile-sticky-body-overlay"></div>
<?php include __DIR__.DIRECTORY_SEPARATOR."Includes/sidebar.php"  ?>
<div class="page-wrapper">
    <!-- Header -->
    <header class="main-header " id="header">
        <nav class="navbar navbar-static-top ">
            <!-- Sidebar toggle button -->
            <button id="sidebar-toggler" class="sidebar-toggle">
                <span class="sr-only">Toggle navigation</span>
            </button>

            <form method="POST" >
                <input type="hidden" name="id" value="<?= $currentUser->id ?>">
                <i class="mdi mdi-logout"></i> <button name="logout" class="btn btn-sm">  Deconnexion</button>
            </form>

        </nav>


    </header>


    <div class="content-wrapper">

        <div class="content">

            <div class="card py-5" >
                <h2 class="ml-3">Clients</h2>

                <div class="mx-5 mb-2">
                    <button type="button" class="btn btn-info btn-pill btn-md float-right w-auto" data-toggle="modal" data-target="#addUser"> ajouter</button>
                </div>
                <hr/>
                <div class="card-body pt-0">
                    <table class="table" id="usersTable">
                        <thead>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Etat</th>
                        <th>dernière date de connexion</th>
                        <th class="text-center">Action</th>
                        <th>etat</th>
                        <th>Prenom</th>
                        <th>Nom</th>
                        <th>Tel</th>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <!-- View Detail Modal !-->
        <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="title" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Ajouter Utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form  id="formaddUser" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="username">Utilisateur</label>
                                <input name="username" type="text" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Utilisateur">
                            </div>
                            <div class="form-group">
                                <label for="Email">Email address</label>
                                <input name="email" type="email" class="form-control" id="Email" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Mot De Passe</label>
                                <input name="password" type="password" class="form-control" id="password" placeholder="Mot de Passe">
                            </div>
                            <div class="form-group">
                                <label for="cpasswordHelp">Confirm Mot De Passe</label>
                                <input name="cpassword" type="password" class="form-control" id="cpassword" aria-describedby="cpasswordHelp" placeholder="Confirm Mot de Passe" >
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input name="image" type="file" class="form-control" id="image" >
                            </div>

                            <div class="errors">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="action" value="add_user" />
                            <input type="hidden" name="type" value="C" />
                            <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Fermer</button>
                            <button  type="submit" class="btn btn-primary btn-pill" >Envoyer</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Update  Modal !-->
        <div class="modal fade" id="updateUser" tabindex="-1" role="dialog" aria-labelledby="title" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Modifier Utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form  id="formupdateUser" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">


                            <div class="form-group">
                                <label for="name">Utilisateur</label>
                                <input name="username" type="text" class="form-control" id="name" aria-describedby="usernameHelp" placeholder="Utilisateur">
                            </div>
                            <div class="form-group">
                                <label for="mail">Email address</label>
                                <input name="email" type="email" class="form-control" id="mail" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>

                            <div class="form-group">
                                <label for="img">Image</label>
                                <input name="image" type="file" class="form-control" id="img" >
                            </div>

                            <div class="errors">

                            </div>


                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="action" value="update_user" />
                            <input type="hidden" name="user_id" id="userId" />
                            <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Fermer</button>
                            <button  type="submit" class="btn btn-primary btn-pill" >Envoyer</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- view Modal !-->

        <div class="modal fade" id="viewUser" tabindex="-1" role="dialog" aria-labelledby="title" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title"> Utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="w-100 mx-auto mt-2 mb-5" id="userImg">


                        </div>
                        <div class="row">
                            <div class="col-6 border-right">
                                <span class="font-weight-bold">Pseudo:</span>
                                <p id="username"></p>

                            </div>
                            <div class="col-6">
                                <span class="font-weight-bold">Prenom:</span>
                                <p id="email"></p>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-4">
                                <span class="font-weight-bold">Prenom:</span>
                                <p id="firstname"></p>

                            </div>
                            <div class="col-4 border-left border-right">
                                <span class="font-weight-bold">Nom:</span>
                                <p id="lastname"></p>
                            </div>
                            <div class="col-4">
                                <span class="font-weight-bold">Tel:</span>
                                <p id="tel"></p>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-6 border-right">
                                <p class="text-center" id="status"></p>

                            </div>
                            <div class="col-6">
                                <p  class="text-center" id="datelogin"></p>
                            </div>
                        </div>



                    </div>
                </div>
            </div>


        </div>

        <script>var  show_users="get_clients"</script>
<?php
include __DIR__.DIRECTORY_SEPARATOR."Includes/footer.php"
?>