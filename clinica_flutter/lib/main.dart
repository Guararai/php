import 'package:flutter/material.dart';

import 'screens/lista_animais_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Clínica Veterinária',
      debugShowCheckedModeBanner: false,
      // Material 3 com a cor principal da clínica (verde-azulado)
      theme: ThemeData(
        useMaterial3: true,
        colorSchemeSeed: const Color(0xFF0F766E),
      ),
      home: const ListaAnimaisScreen(),
    );
  }
}
