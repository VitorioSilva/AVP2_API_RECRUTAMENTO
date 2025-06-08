<?php
require_once __DIR__ . '/../models/Pessoa.php';
require_once __DIR__ . '/../utils/Validation.php';
// Controller para endpoints de pessoas
class PessoaController {
    public function create($data) {
        $requiredFields = ['id', 'nome', 'profissao', 'localizacao', 'nivel'];
        
        if (!Validation::validateRequiredFields($data, $requiredFields)) {
            http_response_code(422);
            exit;
        }
        
        if (!Validation::validateUUID($data['id']) || 
            !Validation::validateLocation($data['localizacao']) || 
            !Validation::validateNivel($data['nivel'])) {
            http_response_code(422);
            exit;
        }
        
        try {
            $pessoa = new Pessoa();
            if ($pessoa->exists($data['id'])) {
                http_response_code(422);
                exit;
            }
            
            $pessoa->create($data);
            http_response_code(201);
        } catch (Exception $e) {
            http_response_code(400);
        }
        exit;
    }
}