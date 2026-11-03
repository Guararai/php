import 'dart:convert';

import 'package:http/http.dart' as http;

import '../models/animal.dart';
import '../models/tutor.dart';

/// Erro com uma mensagem pronta para mostrar na tela.
class ApiException implements Exception {
  final String mensagem;
  ApiException(this.mensagem);

  @override
  String toString() => mensagem;
}

/// Centraliza todas as chamadas à API PHP.
class ApiService {
  // Endereço da API. Troque conforme onde o app roda:
  //  - Emulador Android ............ http://10.0.2.2/clinica_php/api
  //  - Celular na mesma rede Wi-Fi . http://IP_DO_PC/clinica_php/api (ex.: 192.168.0.15)
  //  - Chrome ou Windows no PC ..... http://localhost/clinica_php/api
  static const String baseUrl = 'http://10.0.2.2/clinica_php/api';

  static const Duration _tempoLimite = Duration(seconds: 10);

  Future<List<Animal>> listarAnimais() async {
    final List dados = await _get('animais.php');
    return dados.map((item) => Animal.fromJson(item)).toList();
  }

  Future<Animal> buscarAnimal(int id) async {
    return Animal.fromJson(await _get('animais.php?id=$id'));
  }

  Future<Animal> cadastrarAnimal(Animal animal) async {
    final dados = await _executar(() => http.post(
          Uri.parse('$baseUrl/animais.php'),
          headers: {'Content-Type': 'application/json'},
          body: jsonEncode(animal.toJson()),
        ));
    return Animal.fromJson(dados);
  }

  Future<List<Tutor>> listarTutores() async {
    final List dados = await _get('tutores.php');
    return dados.map((item) => Tutor.fromJson(item)).toList();
  }

  Future<dynamic> _get(String caminho) {
    return _executar(() => http.get(Uri.parse('$baseUrl/$caminho')));
  }

  /// Faz a requisição, trata falta de conexão, tempo esgotado e erros da API.
  Future<dynamic> _executar(Future<http.Response> Function() requisicao) async {
    http.Response resposta;
    try {
      resposta = await requisicao().timeout(_tempoLimite);
    } catch (_) {
      throw ApiException(
          'Não foi possível conectar à API. Confira se o XAMPP está ligado e o endereço em api_service.dart.');
    }

    dynamic corpo;
    try {
      // utf8.decode garante que os acentos apareçam certos
      corpo = jsonDecode(utf8.decode(resposta.bodyBytes));
    } catch (_) {
      throw ApiException('A API respondeu algo que não é JSON (código ${resposta.statusCode}).');
    }

    if (resposta.statusCode >= 200 && resposta.statusCode < 300) {
      return corpo;
    }

    // A API manda {"erro": "...", "campos": {...}} quando algo dá errado
    var mensagem = 'Erro ${resposta.statusCode} na API.';
    if (corpo is Map && corpo['erro'] != null) {
      mensagem = corpo['erro'].toString();
      if (corpo['campos'] is Map) {
        mensagem += ' ${(corpo['campos'] as Map).values.join(' ')}';
      }
    }
    throw ApiException(mensagem);
  }
}
