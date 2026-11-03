/// Tutor (dono do animal). Usado no Dropdown da tela de cadastro.
class Tutor {
  final int id;
  final String nome;
  final String telefone;

  Tutor({required this.id, required this.nome, required this.telefone});

  factory Tutor.fromJson(Map<String, dynamic> json) {
    return Tutor(
      id: int.parse(json['id'].toString()),
      nome: json['nome'] ?? '',
      telefone: json['telefone'] ?? '',
    );
  }
}
