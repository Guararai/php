import 'package:flutter/material.dart';

import '../models/animal.dart';
import '../models/tutor.dart';
import '../services/api_service.dart';
import 'lista_animais_screen.dart';

/// Formulário que envia um novo animal para a API (POST).
class CadastroAnimalScreen extends StatefulWidget {
  const CadastroAnimalScreen({super.key});

  @override
  State<CadastroAnimalScreen> createState() => _CadastroAnimalScreenState();
}

class _CadastroAnimalScreenState extends State<CadastroAnimalScreen> {
  final _api = ApiService();
  final _formKey = GlobalKey<FormState>();

  // Um controller para cada campo de texto
  final _nome = TextEditingController();
  final _especie = TextEditingController();
  final _raca = TextEditingController();
  final _idade = TextEditingController();
  final _peso = TextEditingController();
  final _imagemUrl = TextEditingController();

  late Future<List<Tutor>> _tutores; // carregados da API para o Dropdown
  int? _tutorId;
  bool _salvando = false;

  @override
  void initState() {
    super.initState();
    _tutores = _api.listarTutores();
  }

  @override
  void dispose() {
    for (final c in [_nome, _especie, _raca, _idade, _peso, _imagemUrl]) {
      c.dispose();
    }
    super.dispose();
  }

  /// Texto vazio vira null (campo opcional não preenchido).
  String? _textoOuNulo(TextEditingController c) => c.text.trim().isEmpty ? null : c.text.trim();

  Future<void> _salvar() async {
    if (!_formKey.currentState!.validate()) return; // para se algum campo tiver erro

    setState(() => _salvando = true);
    try {
      final animal = Animal(
        nome: _nome.text.trim(),
        especie: _especie.text.trim(),
        raca: _textoOuNulo(_raca),
        idade: int.tryParse(_idade.text.trim()),
        peso: double.tryParse(_peso.text.trim().replaceAll(',', '.')),
        imagemUrl: _textoOuNulo(_imagemUrl),
        tutorId: _tutorId!,
      );
      await _api.cadastrarAnimal(animal);

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Animal cadastrado.')),
      );
      Navigator.pop(context, true); // true avisa a lista que precisa atualizar
    } catch (e) {
      if (!mounted) return;
      setState(() => _salvando = false);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    }
  }

  // ----- Validações dos campos -----
  String? _obrigatorio(String? v) => (v == null || v.trim().isEmpty) ? 'Campo obrigatório' : null;

  String? _validarIdade(String? v) {
    if (v == null || v.trim().isEmpty) return null;
    final idade = int.tryParse(v.trim());
    return (idade == null || idade < 0) ? 'Digite um número inteiro (0 ou mais)' : null;
  }

  String? _validarPeso(String? v) {
    if (v == null || v.trim().isEmpty) return null;
    final peso = double.tryParse(v.trim().replaceAll(',', '.'));
    return (peso == null || peso <= 0) ? 'Digite um peso maior que zero' : null;
  }

  String? _validarUrl(String? v) {
    if (v == null || v.trim().isEmpty) return null;
    final uri = Uri.tryParse(v.trim());
    return (uri == null || !uri.hasScheme || !uri.scheme.startsWith('http'))
        ? 'Digite uma URL começando com http:// ou https://'
        : null;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Cadastrar animal')),
      body: FutureBuilder<List<Tutor>>(
        future: _tutores,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return MensagemTela(
              icone: Icons.cloud_off,
              texto: snapshot.error.toString(),
              aoTentarDeNovo: () => setState(() => _tutores = _api.listarTutores()),
            );
          }
          final tutores = snapshot.data!;
          if (tutores.isEmpty) {
            return const MensagemTela(
              icone: Icons.person_off,
              texto: 'Nenhum tutor cadastrado. Cadastre um tutor no painel web antes de cadastrar animais.',
            );
          }

          return Form(
            key: _formKey,
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                TextFormField(
                  controller: _nome,
                  decoration: const InputDecoration(labelText: 'Nome *', border: OutlineInputBorder()),
                  textCapitalization: TextCapitalization.words,
                  validator: _obrigatorio,
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: _especie,
                  decoration: const InputDecoration(
                    labelText: 'Espécie *',
                    hintText: 'Cachorro, gato, ave...',
                    border: OutlineInputBorder(),
                  ),
                  textCapitalization: TextCapitalization.sentences,
                  validator: _obrigatorio,
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: _raca,
                  decoration: const InputDecoration(labelText: 'Raça', border: OutlineInputBorder()),
                ),
                const SizedBox(height: 12),
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: TextFormField(
                        controller: _idade,
                        decoration: const InputDecoration(labelText: 'Idade (anos)', border: OutlineInputBorder()),
                        keyboardType: TextInputType.number,
                        validator: _validarIdade,
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: TextFormField(
                        controller: _peso,
                        decoration: const InputDecoration(labelText: 'Peso (kg)', border: OutlineInputBorder()),
                        keyboardType: const TextInputType.numberWithOptions(decimal: true),
                        validator: _validarPeso,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: _imagemUrl,
                  decoration: const InputDecoration(labelText: 'URL da foto', border: OutlineInputBorder()),
                  keyboardType: TextInputType.url,
                  validator: _validarUrl,
                ),
                const SizedBox(height: 12),
                // Dropdown com os tutores carregados da API
                DropdownButtonFormField<int>(
                  decoration: const InputDecoration(labelText: 'Tutor *', border: OutlineInputBorder()),
                  items: tutores
                      .map((t) => DropdownMenuItem(value: t.id, child: Text(t.nome)))
                      .toList(),
                  onChanged: (id) => _tutorId = id,
                  validator: (id) => id == null ? 'Escolha o tutor' : null,
                ),
                const SizedBox(height: 24),
                FilledButton.icon(
                  onPressed: _salvando ? null : _salvar,
                  icon: _salvando
                      ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2))
                      : const Icon(Icons.save),
                  label: Text(_salvando ? 'Salvando...' : 'Salvar animal'),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
