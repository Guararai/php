import 'package:flutter/material.dart';

/// Mostra a foto do animal. Sem URL ou com erro no download, mostra um ícone.
class FotoAnimal extends StatelessWidget {
  final String? url;
  final double largura;
  final double altura;

  const FotoAnimal({super.key, this.url, required this.largura, required this.altura});

  @override
  Widget build(BuildContext context) {
    final cores = Theme.of(context).colorScheme;
    final semFoto = Container(
      width: largura,
      height: altura,
      color: cores.primaryContainer,
      child: Icon(Icons.pets, color: cores.onPrimaryContainer, size: altura * 0.45),
    );

    return ClipRRect(
      borderRadius: BorderRadius.circular(8),
      child: (url == null || url!.isEmpty)
          ? semFoto
          : Image.network(
              url!,
              width: largura,
              height: altura,
              fit: BoxFit.cover,
              errorBuilder: (context, erro, pilha) => semFoto,
            ),
    );
  }
}
