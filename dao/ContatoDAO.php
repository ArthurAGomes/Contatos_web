<?php

require_once 'Database.php';
require_once '../model/Contato.php';

class ContatoDAO 
{
    private $db;

    public function __construct()
    {
        $this->db =  Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        try {
            $sql = "SELECT * FROM contatos_info";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function($contato) {
                return new Contato($contato['id'], $contato['nome'], $contato['telefone'], $contato['email']);
            }, $contatos);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM contatos_info WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $contato = $stmt->fetch(PDO::FETCH_ASSOC);
            return $contato ? new Contato($contato['id'], $contato['nome'], $contato['telefone'], $contato['email']) : null;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function create($contato)
{
    verificarAutenticacao();
    
    try {
        $sql = "INSERT INTO contatos_info (nome, telefone, email)
                VALUES (:nome, :telefone, :email)";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':nome' => $contato->getNome(),
            ':telefone' => $contato->getTelefone(),
            ':email' => $contato->getEmail()
        ]);

        return true;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

public function update($contato)
{
    verificarAutenticacao();  
    $userId = $_SESSION['user_id'];

    
    $sql = "SELECT * FROM contatos_info WHERE id = :id AND user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $contato->getId(), ':user_id' => $userId]);

    $contatoExistente = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contatoExistente) {
        echo "Você não tem permissão para editar este contato.";
        return false;
    }

    
    $sql = "UPDATE contatos_info SET nome = :nome, telefone = :telefone, email = :email WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':id' => $contato->getId(),
        ':nome' => $contato->getNome(),
        ':telefone' => $contato->getTelefone(),
        ':email' => $contato->getEmail()
    ]);

    return true;
}


public function delete($id)
{
    verificarAutenticacao();
    $userId = $_SESSION['user_id'];  

    
    $sql = "SELECT * FROM contatos_info WHERE id = :id AND user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $id, ':user_id' => $userId]);

    $contatoExistente = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contatoExistente) {
        echo "Você não tem permissão para excluir este contato.";
        return false;
    }

    $sql = "DELETE FROM contatos_info WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $id]);

    return true;
}
}

?>