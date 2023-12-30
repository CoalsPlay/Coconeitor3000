<?php

namespace App\Models;

use CodeIgniter\Model;

class Match_m extends Model
{
    protected $db;
    protected $table      = ['match_db', 'users_db'];
    protected $primaryKey = 'idMatch';

    public function __construct()
    {
        $this->db = \Config\Database::connect(); 
    }

    // Crear partida
    public function insertMatch($dataPost)
    {
        $sql = $this->db->table('match_db')
                        ->insert($dataPost);
        return $sql;
    }

    // Obtener datos de una partida mediante el token
    public function getMatchByToken($token)
    {
        $sql = $this->db->table('match_db')
                        ->where('token', $token)
                        ->get()
                        ->getResultArray();
                    
        if($sql){
            return $sql[0];
        }else{
            return false;
        }
    }

    // Actualizar partida
    public function updateMatch($idMatch, $parameter, $dataPost)
    {
        $set = [
            $parameter => $dataPost
        ];

        $sql = $this->db->table('match_db')
                        ->where('idMatch', $idMatch)
                        ->update($set);

        return $sql;
    }

    // Sumar +1 partida
    public function sumGames($idMatch, $sumTotal)
    {
        $set = [
            'numGames' => $sumTotal
        ];

        $sql = $this->db->table('match_db')
                        ->where('idMatch', $idMatch)
                        ->update($set);
        return $sql;
    }

    // Borrar partida
    public function deleteMatch($idMatch)
    {
        $sql = $this->db->table('match_db')
                        ->where('idMatch', $idMatch)
                        ->delete();
        return $sql;
    }

}