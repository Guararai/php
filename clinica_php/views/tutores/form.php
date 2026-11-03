<?php
// Função curta para marcar o campo com erro e mostrar a mensagem
$invalido = fn($campo) => isset($erros[$campo]) ? ' is-invalid' : '';
$msg = fn($campo) => isset($erros[$campo]) ? '<div class="invalid-feedback">' . e($erros[$campo]) . '</div>' : '';
?>
<h1 class="h3 mb-3"><?= e($titulo) ?></h1>

<form method="post" action="index.php?controller=tutor&acao=salvar" class="bg-white p-4 rounded shadow-sm" novalidate>
    <input type="hidden" name="id" value="<?= (int) ($tutor['id'] ?? 0) ?>">

    <div class="mb-3">
        <label for="nome" class="form-label">Nome *</label>
        <input type="text" class="form-control<?= $invalido('nome') ?>" id="nome" name="nome"
               value="<?= e($tutor['nome'] ?? '') ?>" maxlength="100" required>
        <?= $msg('nome') ?>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="telefone" class="form-label">Telefone *</label>
            <input type="text" class="form-control<?= $invalido('telefone') ?>" id="telefone" name="telefone"
                   value="<?= e($tutor['telefone'] ?? '') ?>" maxlength="20" placeholder="(11) 91234-5678" required>
            <?= $msg('telefone') ?>
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control<?= $invalido('email') ?>" id="email" name="email"
                   value="<?= e($tutor['email'] ?? '') ?>" maxlength="150">
            <?= $msg('email') ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Salvar tutor</button>
    <a href="index.php?controller=tutor&acao=listar" class="btn btn-link">Cancelar</a>
</form>
