# Parte 2: app Flutter

App com três telas: lista de animais (com puxar para atualizar), detalhes do animal e cadastro.
Usa só o pacote `http`, com `setState` e `FutureBuilder`.

## Como rodar

1. A API da Parte 1 precisa estar rodando (teste `http://localhost/clinica_php/api/animais.php` no navegador).
2. Dentro desta pasta, gere os arquivos de plataforma que não vão para o Git e baixe as dependências:
   ```
   flutter create --project-name clinica_flutter .
   flutter pub get
   ```
   O `flutter create` não sobrescreve os arquivos que já existem (`lib/`, `pubspec.yaml`, `AndroidManifest.xml`).
3. Ajuste o endereço da API em `lib/services/api_service.dart` (`baseUrl`):
   - emulador Android: `http://10.0.2.2/clinica_php/api`
   - celular físico: `http://IP_DO_PC/clinica_php/api` (descubra o IP com `ipconfig` no Windows; celular e PC na mesma rede Wi-Fi)
   - Chrome ou Windows no próprio PC: `http://localhost/clinica_php/api`
4. Rode com `flutter run`.

## Android

O `android/app/src/main/AndroidManifest.xml` já tem a permissão de internet
e `android:usesCleartextTraffic="true"`, que libera HTTP sem SSL para testes com o XAMPP.

## Estrutura

```
lib/
├── main.dart                         tema Material 3 e tela inicial
├── models/animal.dart, tutor.dart    classes com fromJson/toJson
├── services/api_service.dart         listarAnimais, buscarAnimal, cadastrarAnimal, listarTutores
├── screens/                          lista, detalhes e cadastro
└── widgets/foto_animal.dart          foto do animal com ícone quando não há imagem
```

Se o app mostrar "Não foi possível conectar à API", quase sempre é o endereço em `baseUrl`
ou o firewall do Windows bloqueando o Apache.
