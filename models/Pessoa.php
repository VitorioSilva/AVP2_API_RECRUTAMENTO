<?php
// Modelo para manipulação de pessoas
class Pessoa {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Cria nova pessoa no banco
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO pessoas (id, nome, profissao, localizacao, nivel)
            VALUES (:id, :nome, :profissao, :localizacao, :nivel)
        ");
        
        return $stmt->execute([
            ':id' => $data['id'],
            ':nome' => $data['nome'],
            ':profissao' => $data['profissao'],
            ':localizacao' => strtoupper($data['localizacao']),
            ':nivel' => $data['nivel']
        ]);
    }

    // Verifica se pessoa existe
    public function exists($id) {
        $stmt = $this->db->prepare("SELECT id FROM pessoas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Obtém pessoa por ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM pessoas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}