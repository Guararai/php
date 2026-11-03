import 'package:flutter/material.dart';

import '../models/animal.dart';
import '../services/api_service.dart';
import '../widgets/foto_animal.dart';
import 'lista_animais_screen.dart';

/// Mostra todos os dados de um animal, buscados na API pelo id.
class DetalhesAnimalScreen extends StatefulWidget {
  final int id;
  final String nome; // já aparece no título enquanto carrega

  const DetalhesAnimalScreen({super.key, required this.id, required this.nome});

  @override
  State<DetalhesAnimalScreen> createState() => _DetalhesAnimalScreenState();
}

class _DetalhesAnimalScreenState extends State<DetalhesAnimalScreen> {
  final _api = ApiService();
  late Future<Animal> _animal;

  @override
  void initState() {
    super.initState();
    _animal = _api.buscarAnimal(widget.id);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(widget.nome)),
      body: FutureBuilder<Animal>(
        future: _animal,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return MensagemTela(
              icone: Icons.cloud_off,
              texto: snapshot.error.toString(),
              aoTentarDeNovo: () => setState(() {
                _animal = _api.buscarAnimal(widget.id);
              }),
            );
          }

          final a = snapshot.data!;
          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              FotoAnimal(url: a.imagemUrl, largura: double.infinity, altura: 240),
              const SizedBox(height: 16),
              Text(a.nome, style: Theme.of(context).textTheme.headlineMedium),
              const SizedBox(height: 8),
              _Info(icone: Icons.pets, titulo: 'Espécie', valor: a.especie),
              _Info(icone: Icons.category, titulo: 'Raça', valor: a.raca ?? 'Não informada'),
              _Info(
                icone: Icons.cake,
                titulo: 'Idade',
                valor: a.idade == null ? 'Não informada' : '${a.idade} ano(s)',
              ),
              _Info(
                icone: Icons.monitor_weight,
                titulo: 'Peso',
                // 12.8 vira "12,80 kg"
                valor: a.peso == null ? 'Não informado' : '${a.peso!.toStringAsFixed(2).replaceAll('.', ',')} kg',
              ),
              const Divider(),
              _Info(icone: Icons.person, titulo: 'Tutor', valor: a.tutorNome ?? '-'),
              _Info(icone: Icons.phone, titulo: 'Telefone do tutor', valor: a.tutorTelefone ?? '-'),
            ],
          );
        },
      ),
    );
  }
}

/// Uma linha de informação com ícone, título e valor.
class _Info extends StatelessWidget {
  final IconData icone;
  final String titulo;
  final String valor;

  const _Info({required this.icone, required this.titulo, required this.valor});

  @override
  Widget build(BuildContext context) {
    return ListTile(
      contentPadding: EdgeInsets.zero,
      leading: Icon(icone, color: Theme.of(context).colorScheme.primary),
      title: Text(titulo),
      subtitle: Text(valor),
    );
  }
}
