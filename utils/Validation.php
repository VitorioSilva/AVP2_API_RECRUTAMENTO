<?php
// Validações conforme requisitos do desafio
class Validation {
    // Valida formato UUID v4
    public static function validateUUID($uuid) {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid);
    }
    
    // Valida localizações de A a F (únicas)
    public static function validateLocation($localizacao) {
        return preg_match('/^[A-F]$/i', $localizacao);
    }
    
    // Valida níveis de 1 a 5
    public static function validateNivel($nivel) {
        return is_numeric($nivel) && $nivel >= 1 && $nivel <= 5;
    }
    
    // Verifica campos obrigatórios
    public static function validateRequiredFields($data, $fields) {
        foreach ($fields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }
        return true;
    }
}