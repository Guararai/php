<?php $logado = !empty($_SESSION['usuario_id']); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Clínica veterinária') ?> | Clínica veterinária</title>
    <!-- Bootstrap via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Cor principal da clínica: verde-azulado */
        .navbar-clinica { background: #0f766e; }
        .btn-primary { --bs-btn-bg: #0f766e; --bs-btn-border-color: #0f766e;
                       --bs-btn-hover-bg: #115e59; --bs-btn-hover-border-color: #115e59;
                       --bs-btn-active-bg: #134e4a; --bs-btn-active-border-color: #134e4a; }
        .foto-animal { width: 56px; height: 56px; object-fit: cover; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-md navbar-dark navbar-clinica mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Clínica veterinária</a>
        <?php if ($logado): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                    aria-controls="menu" aria-expanded="false" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=animal&acao=listar">Animais</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=tutor&acao=listar">Tutores</a></li>
                </ul>
                <span class="navbar-text me-3">Olá, <?= e($_SESSION['usuario_nome']) ?></span>
                <a class="btn btn-outline-light btn-sm" href="index.php?controller=auth&acao=logout">Sair</a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<main class="container pb-5">
<?php if (!empty($_SESSION['mensagem'])): ?>
    <!-- Mensagem de sucesso/erro deixada pela ação anterior -->
    <div class="alert alert-<?= e($_SESSION['mensagem']['tipo']) ?> alert-dismissible fade show">
        <?= e($_SESSION['mensagem']['texto']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    <?php unset($_SESSION['mensagem']); ?>
<?php endif; ?>
