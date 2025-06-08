<?php
// Modelo para manipulação de vagas
class Vaga {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Cria nova vaga no banco
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO vagas (id, empresa, titulo, descricao, localizacao, nivel)
            VALUES (:id, :empresa, :titulo, :descricao, :localizacao, :nivel)
        ");
        
        return $stmt->execute([
            ':id' => $data['id'],
            ':empresa' => $data['empresa'],
            ':titulo' => $data['titulo'],
            ':descricao' => $data['descricao'] ?? null,
            ':localizacao' => strtoupper($data['localizacao']),
            ':nivel' => $data['nivel']
        ]);
    }

    // Verifica se vaga existe
    public function exists($id) {
        $stmt = $this->db->prepare("SELECT id FROM vagas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Obtém vaga por ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM vagas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}