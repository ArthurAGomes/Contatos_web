<?php

require_once '../dao/Database.php';
require_once '../model/Usuario.php';

class UsuarioDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($usuario) 
    {
        try {
            $sql = "INSERT INTO usuario (nome, senha, email, token) 
            VALUES (:nome, :senha, :email, :token)";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':nome' => $usuario->getNome(),
                ':senha' => $usuario->getSenha(),
                ':email' => $usuario->getEmail(),
                ':token' => $usuario->getToken()
            ]);

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getByEmail($email)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE Email = :email";
            $stmt = $this->db->prepare($sql);

            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $usuario ? new Usuario($usuario['id'], $usuario['nome'], $usuario['senha'], $usuario['email'], $usuario['token']) : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateToken($id, $token)
    {
        try {
            $sql = "UPDATE usuario SET token = :token WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id, ':token' => $token]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>