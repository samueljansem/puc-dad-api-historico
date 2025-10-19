# API de Historico Escolar

API para gerenciamento de historicos escolares de alunos.

## Endpoints Disponiveis

Todos os endpoints seguem o padrao: `historico.php?matricula={matricula}`

### GET - Consultar Historico

Retorna o historico escolar de um aluno especifico.

**Requisicao:**

```
GET /api/alunos/123/historico
```

**Resposta de Sucesso (200):**

```json
{
  "estudante": 123,
  "tipo_ensino": "publico",
  "ano_desde_ensino_medio": 2020,
  "fez_cursinho": 1,
  "possui_bolsa": 0
}
```

**Resposta de Erro (404):**

```json
{
  "error": "Historico not found"
}
```

### POST - Criar Historico

Cria um novo registro de historico escolar para um aluno.

**Requisicao:**

```
POST /api/alunos/123/historico
Content-Type: application/x-www-form-urlencoded

tipo_ensino=publico&ano_desde_ensino_medio=2020&fez_cursinho=1&possui_bolsa=0
```

**Parametros:**

- `tipo_ensino` (obrigatorio): Tipo de ensino do aluno
- `ano_desde_ensino_medio` (opcional): Ano de conclusao do ensino medio
- `fez_cursinho` (opcional): Se o aluno fez cursinho (0 ou 1)
- `possui_bolsa` (opcional): Se o aluno possui bolsa (0 ou 1)

**Resposta de Sucesso (200):**

```json
{
  "success": true
}
```

**Resposta de Erro (400):**

```json
{
  "error": "O tipo de ensino e obrigatorio"
}
```

### PATCH/PUT - Atualizar Historico

Atualiza o historico escolar de um aluno existente.

**Requisicao:**

```
PATCH /api/alunos/123/historico
Content-Type: application/x-www-form-urlencoded

matricula=123&tipo_ensino=particular&ano_desde_ensino_medio=2021&fez_cursinho=0&possui_bolsa=1
```

**Parametros:**

- `matricula` (obrigatorio): Matricula do aluno
- `tipo_ensino` (obrigatorio): Tipo de ensino do aluno
- `ano_desde_ensino_medio` (opcional): Ano de conclusao do ensino medio
- `fez_cursinho` (opcional): Se o aluno fez cursinho (0 ou 1)
- `possui_bolsa` (opcional): Se o aluno possui bolsa (0 ou 1)

**Resposta de Sucesso (200):**

```json
{
  "success": true
}
```

**Resposta de Erro (400):**

```json
{
  "error": "A matricula e tipo de ensino sao obrigatorios"
}
```

### DELETE - Remover Historico

Remove o historico escolar de um aluno.

**Requisicao:**

```
DELETE /api/alunos/123/historico
Content-Type: application/x-www-form-urlencoded

matricula=123
```

**Parametros:**

- `matricula` (obrigatorio): Matricula do aluno

**Resposta de Sucesso (200):**

```json
{
  "success": true
}
```

**Resposta de Erro (400):**

```json
{
  "error": "A matricula e obrigatoria"
}
```

## Validacao de Matricula

A API realiza validacoes em todas as requisicoes antes de processar qualquer operacao:

### 1. Validacao de Formato

O sistema verifica se a matricula esta no formato correto atraves do metodo `isValidFormat()` em [AlunoValidator.php:83-86](AlunoValidator.php#L83-L86).

**Criterios:**

- A matricula deve ser numerica
- Deve ser um numero inteiro positivo maior que zero
- Nao pode conter caracteres nao numericos

**Implementacao:**

```php
return is_numeric($matricula) && intval($matricula) > 0 && intval($matricula) == $matricula;
```

Se a validacao falhar, retorna erro 400:

```json
{
  "error": "Formato de matricula invalido",
  "matricula": "abc"
}
```

### 2. Validacao de Existencia

Apos validar o formato, o sistema verifica se o aluno existe atraves do metodo `exists()` em [AlunoValidator.php:13-43](AlunoValidator.php#L13-L43).

**Processo:**

1. Faz uma requisicao HTTP GET para a API de Alunos
2. Utiliza cURL com as seguintes configuracoes:
   - Timeout de 10 segundos
   - Timeout de conexao de 5 segundos
   - Verifica certificado SSL
   - Segue redirecionamentos
   - Headers customizados para identificacao
3. Verifica o codigo de resposta HTTP:
   - **200**: Aluno existe, validacao bem-sucedida
   - **Outros codigos**: Aluno nao encontrado

Se a validacao falhar, retorna erro 404:

```json
{
  "error": "Aluno nao encontrado",
  "matricula": "999"
}
```

### Fluxo de Validacao

O fluxo completo de validacao em [historico.php:17-35](historico.php#L17-L35) segue esta ordem:

1. Verifica se a matricula foi fornecida na query string
2. Valida o formato da matricula
3. Verifica se o aluno existe na API externa
4. Se todas as validacoes passarem, processa a requisicao

Qualquer falha em uma das etapas interrompe o processamento e retorna o erro apropriado.

## Codigos de Status HTTP

- **200**: Sucesso
- **400**: Requisicao invalida (matricula ausente ou formato invalido)
- **404**: Aluno ou historico nao encontrado
- **405**: Metodo HTTP nao permitido

## CORS

A API esta configurada para aceitar requisicoes de qualquer origem (Access-Control-Allow-Origin: \*).
