<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Tutores</h1>
    <a class="btn btn-primary" href="index.php?controller=tutor&acao=novo">Cadastrar tutor</a>
</div>

<?php if (!$tutores): ?>
    <p class="text-muted">Nenhum tutor cadastrado ainda.</p>
<?php else: ?>
<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr><th>Nome</th><th>Telefone</th><th>E-mail</th><th>Animais</th><th class="text-end">Ações</th></tr>
        </thead>
        <tbody>
        <?php foreach ($tutores as $t): ?>
            <tr>
                <td><?= e($t['nome']) ?></td>
                <td><?= e($t['telefone']) ?></td>
                <td><?= e($t['email'] ?? '') ?></td>
                <td><?= (int) $t['total_animais'] ?></td>
                <td class="text-end text-nowrap">
                    <a class="btn btn-sm btn-outline-secondary" href="index.php?controller=tutor&acao=editar&id=<?= (int) $t['id'] ?>">Editar</a>
                    <!-- Exclusão por POST, com confirmação -->
                    <form method="post" action="index.php?controller=tutor&acao=excluir" class="d-inline"
                          onsubmit="return confirm(<?= e(json_encode('Excluir o tutor ' . $t['nome'] . '?')) ?>);">
                        <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
