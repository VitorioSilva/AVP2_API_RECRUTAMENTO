<?php
// Modelo para manipulação de candidaturas e cálculo de scores
class Candidatura {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Calcula a distância entre localizações usando Dijkstra
    private function calculateDistance($from, $to) {
        $graph = [
            'A' => ['B' => 5],
            'B' => ['A' => 5, 'C' => 7, 'D' => 3],
            'C' => ['B' => 7, 'E' => 4],
            'D' => ['B' => 3, 'E' => 10, 'F' => 8],
            'E' => ['C' => 4, 'D' => 10],
            'F' => ['D' => 8]
        ];

        // Implementação do Dijkstra (menor caminho)
        $dist = array_fill_keys(array_keys($graph), INF);
        $dist[$from] = 0;
        $queue = new SplPriorityQueue();
        $queue->insert($from, 0);

        while (!$queue->isEmpty()) {
            $u = $queue->extract();
            if ($u === $to) break;

            foreach ($graph[$u] as $v => $cost) {
                if ($dist[$u] + $cost < $dist[$v]) {
                    $dist[$v] = $dist[$u] + $cost;
                    $queue->insert($v, -$dist[$v]);
                }
            }
        }

        return $dist[$to] ?? INF;
    }

    // Calcula o score conforme fórmula do desafio
    public function calculateScore($id_vaga, $id_pessoa) {
        $vaga = (new Vaga())->getById($id_vaga);
        $pessoa = (new Pessoa())->getById($id_pessoa);

        if (!$vaga || !$pessoa) {
            throw new Exception("Vaga ou pessoa não encontrada");
        }

        // Cálculo de N
        $N = 100 - 25 * abs($vaga['nivel'] - $pessoa['nivel']);

        // Cálculo de D
        $distance = $this->calculateDistance(
            $pessoa['localizacao'],
            $vaga['localizacao']
        );
        $D = match(true) {
            $distance <= 5 => 100,
            $distance <= 10 => 75,
            $distance <= 15 => 50,
            $distance <= 20 => 25,
            default => 0
        };

        // Parte inteira conforme desafio
        return (int)(($N + $D) / 2);
    }

    // Cria nova candidatura
    public function create($data) {
        $score = $this->calculateScore($data['id_vaga'], $data['id_pessoa']);

        $stmt = $this->db->prepare("
            INSERT INTO candidaturas (id, id_vaga, id_pessoa, score)
            VALUES (:id, :id_vaga, :id_pessoa, :score)
        ");
        
        return $stmt->execute([
            ':id' => $data['id'],
            ':id_vaga' => $data['id_vaga'],
            ':id_pessoa' => $data['id_pessoa'],
            ':score' => $score
        ]);
    }

    // Verifica se candidatura já existe
    public function exists($id_vaga, $id_pessoa) {
        $stmt = $this->db->prepare("SELECT id FROM candidaturas WHERE id_vaga = ? AND id_pessoa = ?");
        $stmt->execute([$id_vaga, $id_pessoa]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Obtém ranking para uma vaga
    public function getRankingByVaga($id_vaga) {
        // Primeiro verifica se a vaga existe
        $vagaExists = (new Vaga())->exists($id_vaga);
        if (!$vagaExists) {
            return null; // indica que a vaga não existe
        }
        
        $stmt = $this->db->prepare("
            SELECT p.nome, p.profissao, p.localizacao, p.nivel, c.score
            FROM candidaturas c
            JOIN pessoas p ON c.id_pessoa = p.id
            WHERE c.id_vaga = ?
            ORDER BY c.score DESC
        ");
        $stmt->execute([$id_vaga]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return empty($result) ? null : $result; // retorna null se não houver candidaturas
    }
}