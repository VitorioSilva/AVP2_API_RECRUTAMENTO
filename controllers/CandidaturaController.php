<?php
require_once __DIR__ . '/../models/Candidatura.php';
// Controller para endpoints de candidaturas
class CandidaturaController {
    public function create($data) {
        $requiredFields = ['id', 'id_vaga', 'id_pessoa'];
        
        if (!Validation::validateRequiredFields($data, $requiredFields)) {
            http_response_code(422);
            exit;
        }
        
        try {
            $candidatura = new Candidatura();
            // Verifica duplicidade
            if ($candidatura->exists($data['id_vaga'], $data['id_pessoa'])) {
                http_response_code(400);
                exit;
            }
            
            // Cria candidatura
            $candidatura->create($data);
            http_response_code(201);
        } catch (Exception $e) {
            // Personaliza código de erro
            if (str_contains($e->getMessage(), "não encontrada")) {
                http_response_code(404);
            } else {
                http_response_code(400);
            }
        }
        exit;
    }
    
    public function getRanking($id_vaga) {
        try {
            $candidatura = new Candidatura();
            $ranking = $candidatura->getRankingByVaga($id_vaga);

            if ($ranking === null) {
                http_response_code(404); // Vaga não existe
                exit;
            }
            
            header('Content-Type: application/json');
            echo json_encode($ranking);
        } catch (Exception $e) {
            http_response_code(404);
        }
        exit;
    }
}