<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Animais</h1>
    <a class="btn btn-primary" href="index.php?controller=animal&acao=novo">Cadastrar animal</a>
</div>

<?php if (!$animais): ?>
    <p class="text-muted">Nenhum animal cadastrado ainda.</p>
<?php else: ?>
<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr><th></th><th>Nome</th><th>Espécie / raça</th><th>Idade</th><th>Peso</th><th>Tutor</th><th class="text-end">Ações</th></tr>
        </thead>
        <tbody>
        <?php foreach ($animais as $a): ?>
            <tr>
                <td>
                    <?php if ($a['imagem_url']): ?>
                        <img src="<?= e($a['imagem_url']) ?>" alt="" class="foto-animal rounded">
                    <?php endif; ?>
                </td>
                <td><?= e($a['nome']) ?></td>
                <td><?= e($a['especie']) ?><?= $a['raca'] ? ' / ' . e($a['raca']) : '' ?></td>
                <td><?= $a['idade'] !== null ? (int) $a['idade'] . ' ano(s)' : '-' ?></td>
                <td><?= $a['peso'] !== null ? number_format((float) $a['peso'], 2, ',', '.') . ' kg' : '-' ?></td>
                <td><?= e($a['tutor_nome']) ?></td>
                <td class="text-end text-nowrap">
                    <a class="btn btn-sm btn-outline-secondary" href="index.php?controller=animal&acao=editar&id=<?= (int) $a['id'] ?>">Editar</a>
                    <form method="post" action="index.php?controller=animal&acao=excluir" class="d-inline"
                          onsubmit="return confirm(<?= e(json_encode('Excluir o animal ' . $a['nome'] . '?')) ?>);">
                        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
