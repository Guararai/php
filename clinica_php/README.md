# Parte 1: sistema web em PHP (MVC)

## Como rodar no XAMPP

1. Copie esta pasta `clinica_php` para `C:\xampp\htdocs\clinica_php`.
2. No painel do XAMPP, ligue **Apache** e **MySQL**.
3. Abra `http://localhost/phpmyadmin`, vá em **Importar** e envie o arquivo `banco.sql`.
   Ele cria o banco `clinica_veterinaria`, as tabelas e os dados de exemplo.
4. Acesse `http://localhost/clinica_php` e entre com:
   - e-mail: `admin@clinica.com`
   - senha: `admin123`

Se o seu MySQL tiver senha, altere as constantes em `config/Database.php`.

## Estrutura

```
clinica_php/
├── index.php              roteador: index.php?controller=animal&acao=listar
├── banco.sql              criação do banco e dados de exemplo
├── config/Database.php    conexão PDO
├── models/                Usuario, Tutor, Animal (consultas com prepared statements)
├── controllers/           Controller (base), Auth, Tutor, Animal
├── views/                 layout, login, listagens e formulários (Bootstrap via CDN)
└── api/                   endpoints JSON usados pelo app
```

## API

| Método | Endereço | O que faz |
|---|---|---|
| GET | `api/animais.php` | lista os animais com o nome do tutor |
| GET | `api/animais.php?id=1` | retorna um animal (404 se não existir) |
| POST | `api/animais.php` | cadastra um animal (JSON no corpo; 201 se der certo, 400 se faltar dado) |
| GET | `api/tutores.php` | lista os tutores |

Para testar, abra `http://localhost/clinica_php/api/animais.php` no navegador.
Para o POST, use o Postman com o corpo (raw, JSON):

```json
{"nome": "Nina", "especie": "Gato", "raca": "Persa", "idade": 3, "peso": 4.5, "imagem_url": null, "tutor_id": 1}
```

Erros também voltam em JSON, por exemplo `{"erro": "Animal não encontrado."}`.
