import 'package:flutter/material.dart';

import '../models/animal.dart';
import '../services/api_service.dart';
import '../widgets/foto_animal.dart';
import 'cadastro_animal_screen.dart';
import 'detalhes_animal_screen.dart';

/// Tela inicial: lista os animais vindos da API.
class ListaAnimaisScreen extends StatefulWidget {
  const ListaAnimaisScreen({super.key});

  @override
  State<ListaAnimaisScreen> createState() => _ListaAnimaisScreenState();
}

class _ListaAnimaisScreenState extends State<ListaAnimaisScreen> {
  final _api = ApiService();
  late Future<List<Animal>> _animais;

  @override
  void initState() {
    super.initState();
    _animais = _api.listarAnimais();
  }

  /// Busca a lista de novo (puxar para atualizar, botão "Tentar de novo" e após cadastrar).
  Future<void> _recarregar() async {
    final futuro = _api.listarAnimais();
    setState(() => _animais = futuro);
    try {
      await futuro;
    } catch (_) {
      // o erro é mostrado pelo FutureBuilder
    }
  }

  Future<void> _abrirCadastro() async {
    final salvou = await Navigator.push<bool>(
      context,
      MaterialPageRoute(builder: (_) => const CadastroAnimalScreen()),
    );
    if (salvou == true) _recarregar();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Animais')),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _abrirCadastro,
        icon: const Icon(Icons.add),
        label: const Text('Cadastrar animal'),
      ),
      body: RefreshIndicator(
        onRefresh: _recarregar,
        child: FutureBuilder<List<Animal>>(
          future: _animais,
          builder: (context, snapshot) {
            // Carregando pela primeira vez
            if (snapshot.connectionState == ConnectionState.waiting && !snapshot.hasData) {
              return const Center(child: CircularProgressIndicator());
            }
            // API não respondeu ou devolveu erro
            if (snapshot.hasError) {
              return MensagemTela(
                icone: Icons.cloud_off,
                texto: snapshot.error.toString(),
                aoTentarDeNovo: _recarregar,
              );
            }

            final animais = snapshot.data ?? [];
            if (animais.isEmpty) {
              return const MensagemTela(
                icone: Icons.pets,
                texto: 'Nenhum animal cadastrado. Toque em "Cadastrar animal" para adicionar o primeiro.',
              );
            }

            return ListView.separated(
              physics: const AlwaysScrollableScrollPhysics(),
              padding: const EdgeInsets.only(bottom: 88), // espaço para o botão flutuante
              itemCount: animais.length,
              separatorBuilder: (_, __) => const Divider(height: 1),
              itemBuilder: (context, i) {
                final animal = animais[i];
                return ListTile(
                  leading: FotoAnimal(url: animal.imagemUrl, largura: 56, altura: 56),
                  title: Text(animal.nome),
                  subtitle: Text(
                    animal.raca == null ? animal.especie : '${animal.especie}, ${animal.raca}',
                  ),
                  trailing: const Icon(Icons.chevron_right),
                  onTap: () => Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (_) => DetalhesAnimalScreen(id: animal.id!, nome: animal.nome),
                    ),
                  ),
                );
              },
            );
          },
        ),
      ),
    );
  }
}

/// Mensagem centralizada (erro ou lista vazia). É rolável para o "puxar para atualizar" funcionar.
class MensagemTela extends StatelessWidget {
  final IconData icone;
  final String texto;
  final VoidCallback? aoTentarDeNovo;

  const MensagemTela({super.key, required this.icone, required this.texto, this.aoTentarDeNovo});

  @override
  Widget build(BuildContext context) {
    return ListView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.all(32),
      children: [
        const SizedBox(height: 80),
        Icon(icone, size: 64, color: Theme.of(context).colorScheme.outline),
        const SizedBox(height: 16),
        Text(texto, textAlign: TextAlign.center),
        if (aoTentarDeNovo != null) ...[
          const SizedBox(height: 16),
          Center(
            child: FilledButton.tonal(
              onPressed: aoTentarDeNovo,
              child: const Text('Tentar de novo'),
            ),
          ),
        ],
      ],
    );
  }
}
