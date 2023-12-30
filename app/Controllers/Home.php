<?php

namespace App\Controllers;

use App\Models\Match_m;
use App\Models\Users_m;

class Home extends BaseController
{
    protected $matchModel;
    protected $usersModel;

    public function __construct()
    {
        $this->matchModel = new Match_m();
        $this->usersModel = new Users_m();
    }

    public function index()
    {

        $data = [
            'getTopHist' => $this->usersModel->getUsersHist()
        ];

        if(isset($_POST['startMatch'])){

            $teams = $this->request->getPost('numTeams');
            $users = $this->request->getPost('numUsers');
            $game = $this->request->getPost('gameMode');

            if($teams < 2 || $users < 2 || !$game)
            {
                return redirect()->to(current_url())->with('error', 'No puede haber menos de 2 equipos o 2 usuarios o no elegiste un juego.');
            }else{

                $token = bin2hex(openssl_random_pseudo_bytes(64));

                $dataPost = [
                    'numTeams' => $teams,
                    'numUsers' => $users,
                    'rulesMode' => $this->request->getPost('rulesMode'),
                    'date' => date('Y-m-d'),
                    'token' => $token,
                    'gameMode' => $game
                ];

                $query = $this->matchModel->insertMatch($dataPost);

                if(!$query)
                {
                    return redirect()->to(current_url());
                }else{
                    return redirect()->to(base_url("/match/$token"));
                }

            }

        }

        return view('home_v', $data);
    }

    public function Match($token = null)
    { 

        $data = [
            'matchData' => $this->matchModel->getMatchByToken($token),

            'db' => new Users_m(),
            'dbMatch' => new Match_m()
        ];

        if($data['matchData'] == null || $token == 0){ return redirect()->to(base_url()); }

        $data['usersMatch'] = $this->usersModel->getUsersByIdMatch($data['matchData']['idMatch']);
        $data['usersMatchRandom'] = $this->usersModel->getUsersByIdMatch($data['matchData']['idMatch']);
        $data['numUsersByMatch'] = $this->usersModel->numUsersByMatch($data['matchData']['idMatch']);
        $data['getTopUsers'] = $this->usersModel->getUserByPoints($data['matchData']['idMatch']);

        if(isset($_POST['registerUsers']))
        {

            for($i = 0; $i < $data['matchData']['numUsers']; $i++)
            {

                $validation = $this->validate([
                    'user_'.$i => [
                        'rules'  => 'required|alpha_dash|htmlspecialchars',
                        'errors' => [
                            'required' => 'Debe escribir un nombre de usuario '.($i + 1).'<br/>',
                            'alpha_dash' => 'No está permitido carácteres especiales.<br/>',
                            'htmlspecialchars' => 'No se permiten carácteres especiales.<br/>',
                        ],
                    ],
                ]);
            }

            if(!$validation){

                // Imprimir vistas de la página en caso de error.
                return view('match_v', $data, ['validation'=>$this->validator]);
    
            }else{

                for($i = 0; $i < $data['matchData']['numUsers']; $i++)
                {
                    $dataPost = [
                        'idUserMatch' => $data['matchData']['idMatch'],
                        'username' => $this->request->getPost('user_'.$i),
                    ];
                
                    $query = $this->usersModel->insertUsers($dataPost);
                }

                if(!$query){
                    return redirect()->to(current_url())->with('error', 'Hubo algún error.');
                }else{
                    return redirect()->to(current_url());
                }
                
            }
        }

        // Agregar usuario extra
        if(isset($_POST['addUser']))
        {
            $validation = $this->validate([
                'newUser' => [
                    'rules'  => 'required|alpha_dash|htmlspecialchars',
                    'errors' => [
                        'required' => 'Debe escribir un nombre de usuario.<br/>',
                        'alpha_dash' => 'No está permitido carácteres especiales.<br/>',
                        'htmlspecialchars' => 'No se permiten carácteres especiales.<br/>',
                    ],
                ],
            ]);

            if(!$validation){

                // Imprimir vistas de la página en caso de error.
                return view('match_v', $data, ['validation'=>$this->validator]);
    
            }else{

                $dataPost = [
                    'idUserMatch' => $data['matchData']['idMatch'],
                    'username' => $this->request->getPost('newUser')
                ];

                $query = $this->usersModel->insertUsers($dataPost);

                $sumUsers = $data['matchData']['numUsers'] + 1;
                $query2 = $this->matchModel->updateMatch($data['matchData']['idMatch'], 'numUsers', $sumUsers);

                if(!$query && !$query2){
                    return redirect()->to(current_url())->with('error', 'Hubo algún error.');
                }else{
                    return redirect()->to(current_url());
                }
                
            }
        }

        // Botón de borrar partida sin introducir perdedor al ranking
        if(isset($_POST['deleteMatch']))
        {
            $query = $this->matchModel->deleteMatch($data['matchData']['idMatch']);

            if($query){
                $query2 = $this->usersModel->deleteUsersByMatch($data['matchData']['idMatch']);
                return redirect()->to(current_url());
            }else{
                return redirect()->to(current_url())->with('error', 'Hubo algún error.');
            }
        }

        // Botón de eliminar jugador
        if(isset($_POST['deleteUser']))
        {
            $idUser = $this->request->getPost('idUser');

            $query = $this->usersModel->deleteUserById($data['matchData']['idMatch'], $idUser);

            if($query){

                $restPlayer = $data['matchData']['numUsers'] - 1;
                $query2 = $this->matchModel->updateMatch($data['matchData']['idMatch'], 'numUsers', $restPlayer);
                return redirect()->to(current_url());
            }else{
                return redirect()->to(current_url());
            }
        }

        // Botón de sumar +1 punto a un jugador
        if($this->request->getPost('sumOne'))
        {
            $idUser = $this->request->getPost('idUser');
            $pointsUser = $this->request->getPost('pointsUser');
            
            $sumPoints = $pointsUser + 1;

            $query = $this->usersModel->updateUser($idUser, 'points', $sumPoints);

            if($query){
                return redirect()->to(current_url());
            }
        }

        // Botón de sumar +2 puntos a un jugador
        if($this->request->getPost('sumTwo'))
        {
            $idUser = $this->request->getPost('idUser');
            $pointsUser = $this->request->getPost('pointsUser');
            
            $sumPoints = $pointsUser + 2;

            $query = $this->usersModel->updateUser($idUser, 'points', $sumPoints);

            if($query){
                return redirect()->to(current_url());
            }
        }

        // Botón de restar punto a un jugador
        if($this->request->getPost('restOne'))
        {
            $idUser = $this->request->getPost('idUser');
            $pointsUser = $this->request->getPost('pointsUser');
            
            $sumPoints = $pointsUser - 1;

            if($pointsUser > 0){
                $query = $this->usersModel->updateUser($idUser, 'points', $sumPoints);

                if($query){
                    return redirect()->to(current_url());
                }
                
            }else{
                return redirect()->to(current_url());
            }
        }

        // Botón de derrota por equipo suma a todos sus miembros +1
        if($this->request->getPost('defeat'))
        {
            $query = null;
            $dataMatchUpdate = $this->matchModel->getMatchByToken($token);

           for($j = 1; $j < ($data['matchData']['numUsers'] / $data['matchData']['numTeams'] + 1); $j++){
                $idUserDefeat = $this->request->getPost("idUserDefeat_$j");
                $pointsUserDefeat = $this->request->getPost("pointsUserDefeat_$j") + 1;
                $query = $this->usersModel->updateUser($idUserDefeat, 'points', $pointsUserDefeat);
            }
            
            foreach($data['usersMatch'] as $row){
                $sumNumGames = $row['numGamesUser'] + 1;
                $query2 = $this->usersModel->updateUser($row['idUser'], 'numGamesUser', $sumNumGames);
            }

            $sumTotal = $data['matchData']['numGames'] + 1;
            $query3 = $this->matchModel->sumGames($data['matchData']['idMatch'], $sumTotal);

            if($query && $query2 && $query3){
                return redirect()->to(current_url());
            }else{
                return redirect()->to(current_url());
            }
        
        }

        // Botón de derrota por equipo suma a todos sus miembros +2
        if($this->request->getPost('defeatDouble'))
        {
            $query = null;
            $dataMatchUpdate = $this->matchModel->getMatchByToken($token);

           for($j = 1; $j < ($data['matchData']['numUsers'] / $data['matchData']['numTeams'] + 1); $j++){
                $idUserDefeat = $this->request->getPost("idUserDefeat_$j");
                $pointsUserDefeat = $this->request->getPost("pointsUserDefeat_$j") + 2;
                $query = $this->usersModel->updateUser($idUserDefeat, 'points', $pointsUserDefeat);
            }
            
            foreach($data['usersMatch'] as $row){
                $sumNumGames = $row['numGamesUser'] + 1;
                $query2 = $this->usersModel->updateUser($row['idUser'], 'numGamesUser', $sumNumGames);
            }

            $sumTotal = $data['matchData']['numGames'] + 1;
            $query3 = $this->matchModel->sumGames($data['matchData']['idMatch'], $sumTotal);

            if($query && $query2 && $query3){
                return redirect()->to(current_url());
            }else{
                return redirect()->to(current_url());
            }
        
        }

        // Resetear partida menos usuarios
        if($this->request->getPost('resetPoints'))
        {
            $query = $this->usersModel->resetPoints($data['matchData']['idMatch']);
            $query2 = $this->matchModel->sumGames($data['matchData']['idMatch'], 0);
            $query3 = $this->usersModel->updateUserByMatch($data['matchData']['idMatch'], 'numGamesUser', 0);

            if($query){
                return redirect()->to(current_url());
            }else{
                return redirect()->to(current_url())->with('error', 'Hubo algún error.');
            }
        }

        // Sumar +1 a un jugador
        if(isset($_POST['sumGame']))
        {
            foreach($data['usersMatch'] as $row){
                $sumNumGames = $row['numGamesUser'] + 1;
                $query2 = $this->usersModel->updateUser($row['idUser'], 'numGamesUser', $sumNumGames);
            }

            $sumTotal = $data['matchData']['numGames'] + 1;
            $query = $this->matchModel->sumGames($data['matchData']['idMatch'], $sumTotal);

            if($query){
                return redirect()->to(current_url());
            }
        }

        // Terminar partida y anotar al perdedor en el ranking principal
        if(isset($_POST['finishMatch']))
        {
            date_default_timezone_set("Europe/Madrid");

            $dataPost = [
                'userHist' => $this->request->getPost('losingUser'),
                'pointsHist' => intval($this->request->getPost('pointsLosingUser')),
                'gamesHist' => intval($data['matchData']['numGames']),
                'gamesPlayedHist' => intval($this->request->getPost('gamesPlayedHist')),
                'dateHist' => date('j/m/Y G:i'),
                'gameMode' => $data['matchData']['gameMode']
            ];

            if($dataPost['gamesHist'] === 0 || $dataPost['gamesPlayedHist'] === 0 || $dataPost['pointsHist'] === 0)
            {
                return redirect()->to(current_url())->with('error-finish', 'La partida debe tener mínimo <strong>1</strong> partida jugada o algún usuario <strong>1</strong> punto.');
            }else{
                
                $query = $this->usersModel->insertUserHist($dataPost);

                if($query){

                    $query2 = $this->usersModel->deleteUsersByMatch($data['matchData']['idMatch']);
                    $query3 = $this->matchModel->deleteMatch($data['matchData']['idMatch']);
                    return redirect()->to(base_url());
                }else{
                    return redirect()->to(current_url());
                } 
            }

        }

        return view('match_v', $data);
    }
}
