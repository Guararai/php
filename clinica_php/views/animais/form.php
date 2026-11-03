<?php
$invalido = function ($campo) use ($erros) {
    return isset($erros[$campo]) ? ' is-invalid' : '';
};
$msg = function ($campo) use ($erros) {
    return isset($erros[$campo]) ? '<div class="invalid-feedback">' . e($erros[$campo]) . '</div>' : '';
};
$valor = function ($campo) use ($animal) {
    return e($animal[$campo] ?? '');
};
?>
<h1 class="h3 mb-3"><?= e($titulo) ?></h1>

<?php if (!$tutores): ?>
    <div class="alert alert-warning">
        Cadastre um tutor antes de cadastrar animais.
        <a href="index.php?controller=tutor&acao=novo" class="alert-link">Cadastrar tutor</a>
    </div>
<?php endif; ?>

<form method="post" action="index.php?controller=animal&acao=salvar" class="bg-white p-4 rounded shadow-sm" novalidate>
    <input type="hidden" name="id" value="<?= (int) ($animal['id'] ?? 0) ?>">

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nome" class="form-label">Nome *</label>
            <input type="text" class="form-control<?= $invalido('nome') ?>" id="nome" name="nome"
                   value="<?= $valor('nome') ?>" maxlength="100" required>
            <?= $msg('nome') ?>
        </div>
        <div class="col-md-6 mb-3">
            <!-- Select com os tutores vindos do banco (chave estrangeira) -->
            <label for="tutor_id" class="form-label">Tutor *</label>
            <select class="form-select<?= $invalido('tutor_id') ?>" id="tutor_id" name="tutor_id" required>
                <option value="">Escolha o tutor</option>
                <?php foreach ($tutores as $t): ?>
                    <option value="<?= (int) $t['id'] ?>" <?= (string) ($animal['tutor_id'] ?? '') === (string) $t['id'] ? 'selected' : '' ?>>
                        <?= e($t['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?= $msg('tutor_id') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="especie" class="form-label">Espécie *</label>
            <input type="text" class="form-control<?= $invalido('especie') ?>" id="especie" name="especie"
                   value="<?= $valor('especie') ?>" maxlength="50" placeholder="Cachorro, gato, ave..." required>
            <?= $msg('especie') ?>
        </div>
        <div class="col-md-6 mb-3">
            <label for="raca" class="form-label">Raça</label>
            <input type="text" class="form-control" id="raca" name="raca" value="<?= $valor('raca') ?>" maxlength="80">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="idade" class="form-label">Idade (anos)</label>
            <input type="number" min="0" class="form-control<?= $invalido('idade') ?>" id="idade" name="idade" value="<?= $valor('idade') ?>">
            <?= $msg('idade') ?>
        </div>
        <div class="col-md-6 mb-3">
            <label for="peso" class="form-label">Peso (kg)</label>
            <input type="text" inputmode="decimal" class="form-control<?= $invalido('peso') ?>" id="peso" name="peso" value="<?= $valor('peso') ?>">
            <?= $msg('peso') ?>
        </div>
    </div>
    <div class="mb-3">
        <label for="imagem_url" class="form-label">URL da foto</label>
        <input type="url" class="form-control<?= $invalido('imagem_url') ?>" id="imagem_url" name="imagem_url"
               value="<?= $valor('imagem_url') ?>" maxlength="255" placeholder="https://...">
        <?= $msg('imagem_url') ?>
    </div>

    <button type="submit" class="btn btn-primary">Salvar animal</button>
    <a href="index.php?controller=animal&acao=listar" class="btn btn-link">Cancelar</a>
</form>
