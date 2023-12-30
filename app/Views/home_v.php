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
 
    <div class="container overflow-hidden">

        <header class="row text-center">
            <img src="<?= base_url('/assets/img/coconeitor_logo.png'); ?>" style="width:430px; height:150px; margin:0 auto;" alt="">
        </header>

        <div class="row">
            <div class="col-md-4">
                <div class="card" style="margin-bottom:30px;">
                    <h5 class="card-header text-center"><i class="bi bi-joystick"></i> Crear partida</h5>
                    <div class="card-body">

                        <?php if(!empty(session()->getFlashdata('error'))) : ?>
		                   <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
		                <?php endif ?>

                        <form method="post" action="">
                            <?php $validation = \Config\Services::validation(); ?>
                            <?= csrf_field(); ?>
                        
                            <div class="mb-3">
                                <label for="teams" class="form-label">Número de equipos</label>
                                <input type="number" name="numTeams" class="form-control" id="teams" max="8" min="2" value="2">
                            </div>

                            <div class="mb-3">
                                <label for="users" class="form-label">Número de jugadores</label>
                                <input type="number" name="numUsers" class="form-control" id="users" min="2" value="4">
                            </div>

                            <div class="mb-3">
                                <label for="rules">Juegos</label>
                                <select class="form-select" name="gameMode" aria-label="Games">
                                    <option value="0">Elegir juego</option>
                                    <option value="Overwatch">Overwatch</option>
                                    <option value="Rocket League">Rocket League</option>
                                    <option value="Disney Speedstorm">Disney Speedstorm</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="rules">Activar reglas</label>
                                <select class="form-select" name="rulesMode" aria-label="Rules">
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <button type="submit" style="width:100%;margin-top:10px;" name="startMatch" class="btn btn-primary btn-lg"><i class="bi bi-gear-fill"></i> Iniciar Coconeitor</button>
                        </form>

                        <hr>

                        <button class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#faqModal" style="width:100%">
                            <i class="bi bi-question-circle-fill"></i>
                            Guía
                        </button>

                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <h5 class="card-header text-center"><i class='bx bxs-bar-chart-alt-2' ></i> Historial de Cocos</h5>
                    <div class="card-body overflow-auto" style="height:480px;">
                        <?php if(count($getTopHist) > 0){ ?>
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">Nº</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Derrotas</th>
                                <th scope="col">Rondas(<small>Jugadas</small>)</th>
                                <th scope="col">%</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Juego</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach($getTopHist as $row){ ?>
                                <tr class="<?php if($i === 1){ echo 'table-success'; } ?>">
                                <th scope="row"><?= $i++; ?></th>
                                <td><?= $row['userHist']; ?></td>
                                <td><?= $row['pointsHist']; ?></td>
                                <td><?= $row['gamesHist']; ?> (<small><?= $row['gamesPlayedHist']; ?></small>)</td>
                                <td><?= min(ceil(($row['pointsHist'] / $row['gamesPlayedHist']) * 100), 100).'%'; ?></td>
                                <td><?= $row['dateHist']; ?></td>
                                <td><?= $row['gameMode']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                            <?php }else{ ?>
                                <div class="alert alert-info text-center" role="alert">
                                    <strong>Aun no hay registros</strong>
                                </div>
                            <?php } ?>
                    </div>
                </div>
            </div>

        </div>

        <footer class="row text-center" style="margin-top:30px;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        © Coconeitor 3000 | <b>Los CrakerS</b> by <b><a class="link-primary" href="https//x.com/CoalsPlay" target="_blank">CoalsPlay</a></b>
                    </div>
                </div>
            </div> 
        </footer>

    </div>

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

    </body>
</html>
