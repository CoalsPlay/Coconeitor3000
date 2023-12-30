<?php

namespace App\Models;

use CodeIgniter\Model;

class Users_m extends Model
{
    protected $db;
    protected $table      = 'users_db';

    public function __construct()
    {
        $this->db = \Config\Database::connect(); 
    }

    // Insertar usuarios
    public function insertUsers($dataPost)
    {
        $sql = $this->db->table('users_db')
                        ->insert($dataPost);

        return $sql;
    }

    // Obtener usuarios de una partida mediante ID de la partida
    public function getUsersByIdMatch($idMatch)
    {
        $sql = $this->db->table('users_db')
                        ->where('idUserMatch', $idMatch)
                        ->get();

        return $sql->getResultArray();
    }

    // Obtener datos de usuario mediante ID del usuario
    public function getUserById($idUser)
    {
        $sql = $this->db->table('users_db')
                        ->where('idUser', $idUser)
                        ->get();
        return $sql;
    }

    // Obtener número de usuarios de una partida mediante ID de la partida
    public function numUsersByMatch($idMatch)
    {
        return $this->db->table('users_db')
                             ->where('idUserMatch', $idMatch)
                             ->countAllResults();
    }

    // Actualizar puntaje de un usuario mediante ID del usuario
    public function updateUser($idUser, $parameter, $sumPoints)
    {
        $set = [
            $parameter => $sumPoints
        ];

        $sql = $this->db->table('users_db')
                        ->where('idUser', $idUser)
                        ->update($set);
        
        return $sql;
    }

    // Actualizar usuario mediante ID de partida
    public function updateUserByMatch($idMatch, $parameter, $dataPost)
    {
        $set = [
            $parameter => $dataPost
        ];

        $sql = $this->db->table('users_db')
                        ->where('idUserMatch', $idMatch)
                        ->update($set);
        
        return $sql;
    }

    // Obtener puntos de un usuario mediante ID de partida
    public function getUserByPoints($idUserMatch)
    {
        $sql = $this->db->table('users_db')
                        ->where('idUserMatch', $idUserMatch)
                        ->orderBy('points', 'DESC')
                        ->get();

        return $sql->getResultArray();
    }

    // Resetear todos los puntajes de los usuarios de una partida
    public function resetPoints($idMatch)
    {
        $set = [
            'points' => 0
        ];

        $sql = $this->db->table('users_db')
                        ->where('idUserMatch', $idMatch)
                        ->update($set);
        return $sql;
    }

    // Insertar usuario perdedor al ranking global
    public function insertUserHist($dataPost)
    {
        $sql = $this->db->table('historial_db')
                        ->insert($dataPost);
        return $sql;
    }

    // Obtener usuarios del ranking global
    public function getUsersHist()
    {
        $sql = $this->db->table('historial_db')
                        ->orderBy('idHist', 'DESC')
                        ->get();
                        
        return $sql->getResultArray();
    }

    // Borrar usuario mediante ID del usuario y ID de partida
    public function deleteUserById($idMatch, $idUser)
    {
        $sql = $this->db->table('users_db')
                        ->where('idUser', $idUser)
                        ->where('idUserMatch', $idMatch)
                        ->delete();
        return $sql;
    }

    // Borrar usuarios de una partida mediante ID de la partida
    public function deleteUsersByMatch($idMatch)
    {
        $sql = $this->db->table('users_db')
                        ->where('idUserMatch', $idMatch)
                        ->delete();
        return $sql;
    }
}