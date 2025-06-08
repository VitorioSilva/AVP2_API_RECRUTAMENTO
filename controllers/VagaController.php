<?php
require_once __DIR__ . '/../models/Vaga.php';
require_once __DIR__ . '/../utils/Validation.php';
// Controller para endpoints de vagas
class VagaController {
    public function create($data) {
        // Campos obrigatórios
        $requiredFields = ['id', 'empresa', 'titulo', 'localizacao', 'nivel'];
        
        // Validação de campos
        if (!Validation::validateRequiredFields($data, $requiredFields)) {
            http_response_code(422);
            exit;
        }
        
        // Validação de formatos
        if (!Validation::validateUUID($data['id']) || 
            !Validation::validateLocation($data['localizacao']) || 
            !Validation::validateNivel($data['nivel'])) {
            http_response_code(422);
            exit;
        }
        
        try {
            $vaga = new Vaga();
            // Verifica duplicidade
            if ($vaga->exists($data['id'])) {
                http_response_code(422);
                exit;
            }
            
            // Cria vaga
            $vaga->create($data);
            http_response_code(201);
        } catch (Exception $e) {
            http_response_code(400);
        }
        exit;
    }
}