/// Representa um animal da clínica, no mesmo formato do JSON da API.
class Animal {
  final int? id; // nulo quando o animal ainda não foi salvo
  final String nome;
  final String especie;
  final String? raca;
  final int? idade;
  final double? peso;
  final String? imagemUrl;
  final int tutorId;
  final String? tutorNome; // vem do JOIN com a tabela tutores
  final String? tutorTelefone;

  Animal({
    this.id,
    required this.nome,
    required this.especie,
    this.raca,
    this.idade,
    this.peso,
    this.imagemUrl,
    required this.tutorId,
    this.tutorNome,
    this.tutorTelefone,
  });

  /// Cria um Animal a partir do JSON recebido da API.
  factory Animal.fromJson(Map<String, dynamic> json) {
    return Animal(
      id: _paraInt(json['id']),
      nome: json['nome'] ?? '',
      especie: json['especie'] ?? '',
      raca: json['raca'],
      idade: _paraInt(json['idade']),
      peso: _paraDouble(json['peso']),
      imagemUrl: json['imagem_url'],
      tutorId: _paraInt(json['tutor_id']) ?? 0,
      tutorNome: json['tutor_nome'],
      tutorTelefone: json['tutor_telefone'],
    );
  }

  /// Converte o Animal em JSON para enviar no cadastro (POST).
  Map<String, dynamic> toJson() {
    return {
      'nome': nome,
      'especie': especie,
      'raca': raca,
      'idade': idade,
      'peso': peso,
      'imagem_url': imagemUrl,
      'tutor_id': tutorId,
    };
  }

  // Aceitam número ou texto ("12.80"), para não quebrar se o tipo vier diferente
  static int? _paraInt(dynamic valor) =>
      valor == null ? null : int.tryParse(valor.toString());

  static double? _paraDouble(dynamic valor) =>
      valor == null ? null : double.tryParse(valor.toString());
}
