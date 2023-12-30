<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Los CrakerS - Coconeitor 3000</title>
        <meta name="description" content="App de uso personal que determina el coco de la noche.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?= base_url('/favicon.ico'); ?>" />

        <!-- CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <!-- JS -->
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </head>
    <body>
 
    <div class="container-xl">

        <header class="row text-center">
            <img src="<?= base_url('/assets/img/coconeitor_logo.png'); ?>" style="width:430px; height:150px; margin: 0 auto;" alt="">
        </header>

        <div class="row">
            <div class="col-12 col-sm-12 <?php if($matchData['gameMode'] === 'Overwatch') { echo 'col-md-7 col-xl-5'; }else{ echo 'col-md-8 col-xl-7'; } ?>">
                <div class="card" style="margin-bottom:30px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Juego: <span class="badge text-bg-primary"><?= $matchData['gameMode']; ?></span></span>
                        <strong>
                        <?php if($numUsersByMatch < $matchData['numUsers']) { ?>
                            <i class="bi bi-people-fill"></i> Introducir usuarios
                        <?php }else{ ?>
                            <i class="bi bi-card-list"></i> Partida
                        <?php } ?>
                        </strong>
                        <button type="submit" data-bs-toggle="modal" data-bs-target="#deleteMatch" class="btn btn-danger btn-sm"><i class='bx bxs-trash'></i> Borrar partida</button>
                    </div>
                    <div class="card-body">

                        <?php  if($numUsersByMatch < $matchData['numUsers']) { ?>

                        <form method="post" action="">
                            <?php $validation = \Config\Services::validation(); ?>
                            <?= csrf_field(); ?>

                            <?php for($i = 0; $i < $matchData['numUsers']; $i++) { ?>
                            <input type="text" class="form-control" value="<?= set_value('user_'.$i) ?>" name="user_<?= $i; ?>" placeholder="Usuario <?= ($i + 1); ?>">
                            <span class="text-danger"><?= $validation->getError('user_'.$i); ?></span><br/>
                            <?php } ?>

                            <button type="submit" name="registerUsers" class="btn btn-primary btn-lg" style="width:100%;"><i class="bi bi-person-plus-fill"></i> Introducir usuarios</button>
                            
                        </form>

                        <?php }else{ ?>

                            <?php if(!empty(session()->getFlashdata('error'))) : ?>
		                        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
		                    <?php endif ?>

                            <div class="row">

                            <div class="col-auto">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#resetPoints"><i class='bx bx-reset'></i> Resetear todo</button>
                            </div>

                                <?php if($matchData['numUsers'] > 0){ ?>
                                <div class="col-auto">
                                    <form method="post" action="<?= current_url(); ?>" class="float-end">
                                        <div class="input-group">
                                            <button class="btn btn-secondary" name="sumGame" type="submit" id="button-addon1"><i class='bx bx-plus'></i> Sumar ronda</button>
                                            <input type="text" class="form-control" readonly value="<?= $matchData['numGames']; ?>" style="width:50px;">
                                        </div>
                                    </form>
                                </div>
                                <?php } ?>
                                
                            </div>
                            
                            <hr>

                            <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">Jugadores (<?= $matchData['numUsers']; ?>)</th>
                                <th scope="col">Acción</th>
                                <th scope="col">Pts.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($usersMatch as $row){ ?>
                                <tr>
                                <td>
                                    <form method="post" action="<?= current_url(); ?>" style="display:flex;">
                                        <input type="hidden" name="idUser" value="<?= $row['idUser']; ?>">
                                        <button type="submit" name="deleteUser" class="btn btn-danger btn-sm"><i class='bx bxs-trash'></i></button>
                                        <span class="badge bg-secondary" style="font-size:18px;width:110px;margin-left:5px;"><?= $row['username']; ?></span></td>
                                    </form>
                                <td>
                                    
                                    <form method="post" action="" class="btn-group" id="sumOne">
                                        <input type="submit" name="sumOne" class="btn btn-warning btn-sm" value="+1" id="sendOne">
                                        <input type="hidden" name="idUser" value="<?= $row['idUser']; ?>">
                                        <input type="hidden" name="pointsUser" value="<?= $row['points']; ?>">
                                    </form>

                                    <?php if($matchData['rulesMode'] == 1){ ?>
                                    <form method="post" action="" class="btn-group">
                                        <input type="submit" name="sumTwo" class="btn btn-danger btn-sm" value="+2">
                                        <input type="hidden" name="idUser" value="<?= $row['idUser']; ?>">
                                        <input type="hidden" name="pointsUser" value="<?= $row['points']; ?>">
                                    </form>
                                    <?php } ?>

                                    <form method="post" action="" class="btn-group">
                                        <input type="submit" name="restOne" class="btn btn-outline-primary btn-sm" value="-1">
                                        <input type="hidden" name="idUser" value="<?= $row['idUser']; ?>">
                                        <input type="hidden" name="pointsUser" value="<?= $row['points']; ?>">
                                    </form>                                    

                                </td>
                                <td><span class="badge bg-dark" style="font-size:18px"><?= $row['points']; ?></span></td>
                                </tr>
                                <?php } // End foreach ?>
                            </tbody>
                            </table>

                            <hr>

                            <?php if(!empty(session()->getFlashdata('error'))) : ?>
		                        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
		                    <?php endif ?>

                            <form method="post" action="<?= current_url(); ?>" class="row g-3">
                                <?php $validation = \Config\Services::validation(); ?>
                                <?= csrf_field(); ?>

                                <span class="text-danger"><?= $validation->getError('newUser'); ?></span>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="newUser" placeholder="Agregar jugador" aria-label="Jugador" aria-describedby="button-addon2">
                                    <button type="submit" class="btn btn-success" name="addUser" id="button-addon2"><i class="bi bi-person-plus-fill"></i></button>
                                </div>
                                
                            </form>

                        <?php } ?>

                        <hr>

                        <button class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#faqModal" style="width:100%">
                        <i class="bi bi-question-circle-fill"></i> Guía
                        </button>

                    </div>
                </div>
            </div>

            <?php if($matchData['gameMode'] === 'Overwatch') { ?>
            <div class="col-12 col-sm-12 col-md-5 col-xl-3">
                <div class="card" style="margin-bottom:30px;">
                    <div class="card-header text-center"><i class="bi bi-dice-5-fill"></i> <b>Equipos aleatorios</b></div>
                    <div class="card-body text-center">
                
                        <?php if($numUsersByMatch > 0) { ?>
                        <form method="post" action="">
                            <button type="submit" name="teamGenerator" class="btn btn-primary" style="width:100%;"><i class="bi bi-arrow-repeat"></i> Generar equipos</button>
                        </form>
                        <hr>
                        <?php } ?>

                        <?php

                            if($numUsersByMatch > 0){
                        
                            if(isset($_POST['teamGenerator'])){

                                // Mezclo todos los parametros del array de forma random
                                shuffle($usersMatchRandom);
                            }

                            // Calcula la cantidad de usuarios por equipo
                            $usersPerTeam = ceil($numUsersByMatch / $matchData['numTeams']);

                            // Divide los usuarios en los equipos
                            $teams = array_chunk($usersMatchRandom, $usersPerTeam); 

                            // Muestra los equipos resultantes
                            for($i = 0; $i < count($teams); $i++) {
                        ?>
                            <div class="card" style="margin-bottom:10px;">
                            <div class="card-header">
                                <b><i class="bi bi-people-fill"></i> Equipo <?= ($i + 1); ?></b>

                                <?php if($matchData['rulesMode'] == 1){ ?> 
                                <form method="post" action="" class="btn-group float-end" id="formTeamDouble_<?= ($i + 1); ?>">
                                    <input type="submit" name="defeatDouble" class="btn btn-outline-danger btn-sm" value="+2">
                                </form>
                                <?php } ?>

                                <form method="post" action="" class="btn-group float-end" style="margin-right:5px;" id="formTeam_<?= ($i + 1); ?>">
                                    <input type="submit" name="defeat" class="btn btn-danger btn-sm" value="+1">
                                </form>                                
                            
                            </div>
                                <ul class="list-group list-group-flush">
                        <?php
                                $j = 1;
                                $p = 1;
                                $n = 1;
                                foreach($teams[$i] as $user) {     
                        ?>
                        
                        <li class="list-group-item"><span class="badge bg-secondary" style="font-size:18px;width:100%;"><?= $user['username']; ?></span></li>
                                <input type="hidden" value="<?= $user['idUser']; ?>" form="formTeam_<?= ($i + 1); ?>" name="idUserDefeat_<?= $j; ?>">
                                <input type="hidden" value="<?= $user['points']; ?>" form="formTeam_<?= ($i + 1); ?>" name="pointsUserDefeat_<?= $p; ?>">
                                <input type="hidden" value="<?= $user['numGamesUser']; ?>" form="formTeam_<?= ($i + 1); ?>" name="numGamesUser_<?= $n; ?>">

                                <input type="hidden" value="<?= $user['idUser']; ?>" form="formTeamDouble_<?= ($i + 1); ?>" name="idUserDefeat_<?= $j++; ?>">
                                <input type="hidden" value="<?= $user['points']; ?>" form="formTeamDouble_<?= ($i + 1); ?>" name="pointsUserDefeat_<?= $p++; ?>">
                                <input type="hidden" value="<?= $user['numGamesUser']; ?>" form="formTeamDouble_<?= ($i + 1); ?>" name="numGamesUser_<?= $n++; ?>">
                        <?php
                                }
                                echo '</ul>
                                        </div>';
                            }
                        ?>
                    <?php }else{ ?>
                        <div class="alert alert-info text-center" role="alert">
                            <strong>Aun no hay usuarios</strong>
                        </div>
                    <?php } // If main ?>                    

                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="col-12 col-sm-12 col-md-12 <?php if($matchData['gameMode'] === 'Overwatch') { echo 'col-xl-4'; }else{ echo 'col-xl-5'; } ?>">
                <div class="card" style="margin-bottom:30px;">
                    <div class="card-header text-center"><b><i class='bx bxs-bar-chart-alt-2'></i> TOP Coco</b></div>
                    <div class="card-body overflow-auto">
                    <?php if(!empty(session()->getFlashdata('error-finish'))) : ?>
		                <div class="alert alert-danger"><?= session()->getFlashdata('error-finish'); ?></div>
		            <?php endif ?>
                          
                    <?php if(count($getTopUsers) > 0){ ?>

                        <form method="post" action="">
                            <input type="hidden" name="losingUser" value="<?= $getTopUsers[0]['username']; ?>">
                            <input type="hidden" name="pointsLosingUser" value="<?= $getTopUsers[0]['points']; ?>">
                            <input type="hidden" name="gamesPlayedHist" value="<?= $getTopUsers[0]['numGamesUser']; ?>">
                            <button type="submit" name="finishMatch" class="btn btn-warning" style="width:100%;"><i class="bi bi-trophy-fill"></i> Finalizar partida</button>
                        </form> 
                        <hr>

                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Jugador</th>
                            <th scope="col">Derrotas</th>
                            <th>Rondas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($getTopUsers as $row){ ?>
                            <tr class="<?php if($i === 1){ echo 'table-danger'; }elseif($i == 2){ echo 'table-warning'; }  ?>">
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $row['username']; ?></td>
                            <td><?= $row['points']; ?></td>
                            <td><?= $matchData['numGames']; ?> (<small><?= $row['numGamesUser']; ?></small>)</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        </table>
                        <?php }else{ ?>
                            <div class="alert alert-info text-center" role="alert">
                                <strong>Aun no hay usuarios</strong>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Modal Resetear partida -->
            <div class="modal fade" id="resetPoints" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="resetPointsLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="resetPointsLabel">¿Estás seguro de reiniciar la partida?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>

                    <form method="post" action="" class="float-start">
                        <input type="submit" name="resetPoints" class="btn btn-primary" value="Aceptar">
                    </form>
                    
                </div>
                </div>
            </div>
            </div>
            <!-- Fin Modal Resetear partida -->

            <!-- Modal Borrar partida -->
            <div class="modal fade" id="deleteMatch" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="resetPointsLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="resetPointsLabel">¿Estás seguro de borrar la partida?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <strong>Esta información no se guardará en la base de datos y no habrá vuelta atrás.</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>

                    <form method="post" action="" class="float-start">
                        <input type="submit" name="deleteMatch" class="btn btn-primary" value="Aceptar">
                    </form>
                    
                </div>
                </div>
            </div>
            </div>
            <!-- Fin Modal Borrar partida -->

            <!-- Modal Guía -->
            <div class="modal fade" id="faqModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="faqModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="faqModal">Modo de uso</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            ¿Qué es Coconeitor 3000?
                        </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>Coconeitor 3000</strong> es una aplicación para puntuar partidas que eches con tus amigos, para determinar quien ha sido el peor del día.
                            <br>
                            A diferencia de otras aplicaciones del estilo, en <strong>Coconeitor 3000</strong> se puntúa las derrotas.
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Creación de partidas
                        </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Para crear partida solo debes introducir cuantos jugadores y equipos tendrá. 
                            <br>
                            <strong>Lista de juegos</strong>: son los que el desarrollador juega con sus amigos y dependiendo del juego que selecciones, tendrás un panel extra para poder aleatorizar los equipos con sus funciones de puntaje que afecta a todos los miembros de ese equipo.
                            <br/>
                            <u>Por ejemplo</u>: Rocket League ya cuenta con un modo para aleatorizar equipos, por lo que no lo necesitaría y por ende no dispondrás de ese panel.
                            <br/>
                            <strong>Modo reglas</strong>: activar el modo reglas habilitará otro botón extra de puntaje en vuestra partida, el cual sumará +2 puntos.
                            <br/>
                            Esto se usaría para situaciones en las que queráis poner ciertas reglas que de cumplirse contaría como +2 puntos.
                            <br/>
                            <u>Por ejemplo</u>: Perder 0-5 en Overwatch o X-10 en Rocket League.
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Consejos de uso
                        </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Una vez creada la partida dispondrás de varias opciones para gestionar toda la información de dicha partida, tanto de los jugadores, como sus puntajes, como el número de partidas que lleváis jugando.
                            <br/>
                            Recuerda que siempre que termines una partida en el juego, en caso de no tener el panel de aleatorizar equipos, tendrás que sumar la partida manualmente después de repartir los puntos a los perdedores de dicha partida. Así cuando termines de jugar, la aplicación podrá calcular el porcentaje de derrotas según las partidas jugadas y disponer de más información.
                            <br>
                            
                            Cuando quieras terminar de jugar, tendrás que darle al botón superior amarillo que pone "Finalizar partida", en el apartado TOP.
                            <br/>
                            Una vez finalizada la partida, el <strong>Coco</strong> del día se reflejará en el ranking de la página principal, donde podrás ir llevando un recuento de quien ha ido siendo el peor de cada día que habéis jugado.
                        </div>
                        </div>
                    </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
                </div>
                </div>
            </div>
            </div>
            <!-- Fin Modal Guía -->
            
        </div>

        <footer class="row text-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        © Coconeitor 3000 | <b>Los CrakerS</b> by <b><a class="link-primary" href="https//x.com/CoalsPlay" target="_blank">CoalsPlay</a></b>
                    </div>
                </div>
            </div> 
        </footer>

    </div>

    </body>
</html>
