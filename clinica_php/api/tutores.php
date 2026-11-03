<?php
/** GET api/tutores.php -> lista os tutores (usado no Dropdown do app) */
require_once __DIR__ . '/base.php';
require_once __DIR__ . '/../models/Tutor.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    erro('Método não permitido.', 405);
}

responder((new Tutor())->listar());
