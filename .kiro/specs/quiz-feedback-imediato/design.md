# Design Document - Sistema de Feedback Imediato em Quizzes

## Overview

O sistema de feedback imediato transforma a experiência de quizzes de um modelo tradicional "responder tudo e depois ver resultado" para um modelo interativo onde cada resposta gera feedback instantâneo com explicações educativas. Isso melhora significativamente o aprendizado e engajamento dos usuários.

## Architecture

### Frontend Architecture
- **Interface Reativa**: JavaScript vanilla para interações imediatas
- **Estados de Questão**: Cada questão terá estados (não respondida, respondida, com feedback)
- **Componentes Visuais**: Cards de feedback, indicadores de progresso, botões de navegação
- **Persistência Local**: LocalStorage para backup de respostas durante a sessão

### Backend Architecture
- **API Endpoints**: Novos endpoints para salvar respostas individuais e buscar explicações
- **Modelo de Dados**: Extensão dos modelos existentes para suportar explicações
- **Validação**: Validação de respostas e geração de feedback estruturado

## Components and Interfaces

### 1. Frontend Components

#### QuizFeedbackManager (JavaScript)
```javascript
class QuizFeedbackManager {
    constructor(quizId, attemptId)
    showFeedback(questionId, selectedAnswer)
    saveAnswer(questionId, answer)
    navigateToNext()
    updateProgress()
}
```

#### FeedbackCard Component
- Exibe feedback visual com cores e ícones
- Mostra explicação da resposta correta
- Inclui botão para próxima questão
- Suporte a animações de transição

#### ProgressTracker Component
- Atualiza progresso em tempo real
- Mostra questões respondidas vs total
- Indicador visual de acertos/erros

### 2. Backend Components

#### QuizAnswerController
```php
class QuizAnswerController {
    public function saveAnswer(Request $request, Quiz $quiz, QuizAttempt $attempt)
    public function getFeedback(QuizQuestion $question, $selectedAnswer)
}
```

#### QuizQuestion Model Extensions
```php
// Novos campos na tabela quiz_questions
- explanation_correct (TEXT) - Explicação da resposta correta
- explanation_incorrect (JSON) - Explicações para respostas incorretas
- feedback_type (ENUM) - Tipo de feedback (immediate, delayed)
```

#### QuizAttemptAnswer Model
```php
// Nova tabela quiz_attempt_answers
- id, attempt_id, question_id, selected_answer
- is_correct, feedback_shown_at, created_at
```

## Data Models

### Database Schema Changes

#### 1. Extensão da tabela `quiz_questions`
```sql
ALTER TABLE quiz_questions ADD COLUMN explanation_correct TEXT;
ALTER TABLE quiz_questions ADD COLUMN explanation_incorrect JSON;
ALTER TABLE quiz_questions ADD COLUMN feedback_type ENUM('immediate', 'delayed') DEFAULT 'immediate';
```

#### 2. Nova tabela `quiz_attempt_answers`
```sql
CREATE TABLE quiz_attempt_answers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attempt_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    selected_answer TEXT NOT NULL,
    is_correct BOOLEAN NOT NULL,
    feedback_shown_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (attempt_id) REFERENCES quiz_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attempt_question (attempt_id, question_id)
);
```

### 3. API Response Structure
```json
{
    "feedback": {
        "is_correct": true,
        "selected_answer": "A",
        "correct_answer": "A",
        "explanation": "Esta é a resposta correta porque...",
        "question_id": 123
    },
    "progress": {
        "current_question": 5,
        "total_questions": 10,
        "correct_answers": 4
    }
}
```

## Error Handling

### Frontend Error Handling
- **Conexão perdida**: Salvar respostas localmente e sincronizar quando reconectar
- **Timeout de sessão**: Alertar usuário e permitir continuação
- **Erro de validação**: Mostrar mensagem clara e permitir nova tentativa

### Backend Error Handling
- **Tentativa inválida**: Retornar erro 403 com mensagem explicativa
- **Questão não encontrada**: Retornar erro 404
- **Dados inválidos**: Retornar erro 422 com detalhes de validação
- **Limite de tempo**: Finalizar automaticamente com respostas parciais

## Testing Strategy

### 1. Unit Tests
- **QuizAnswerController**: Testar salvamento de respostas e geração de feedback
- **QuizQuestion Model**: Testar métodos de explicação e validação
- **JavaScript Components**: Testar lógica de feedback e navegação

### 2. Integration Tests
- **Fluxo completo**: Responder quiz com feedback imediato
- **Persistência**: Verificar salvamento correto de respostas
- **API Endpoints**: Testar todos os endpoints de feedback

### 3. Frontend Tests
- **Interações do usuário**: Simular cliques e seleções
- **Estados visuais**: Verificar cores e ícones corretos
- **Navegação**: Testar transições entre questões

### 4. Performance Tests
- **Tempo de resposta**: Feedback deve aparecer em < 200ms
- **Carregamento**: Questões devem carregar rapidamente
- **Memória**: Verificar uso eficiente de recursos

## Implementation Flow

### 1. Fase de Preparação
- Criar migrações para novos campos e tabelas
- Atualizar modelos com novos relacionamentos
- Criar seeders com dados de exemplo

### 2. Fase Backend
- Implementar novos endpoints de API
- Criar lógica de feedback e validação
- Implementar testes unitários

### 3. Fase Frontend
- Criar componentes JavaScript para feedback
- Implementar interface visual responsiva
- Adicionar animações e transições

### 4. Fase de Integração
- Conectar frontend com backend
- Implementar persistência de estado
- Testes de integração completos

### 5. Fase de Polimento
- Otimizações de performance
- Melhorias de UX/UI
- Testes de usabilidade

## User Experience Flow

### 1. Início do Quiz
```
Usuário clica "Iniciar Quiz" → 
Carrega primeira questão → 
Mostra opções de resposta
```

### 2. Resposta a Questão
```
Usuário seleciona resposta → 
Salva resposta no backend → 
Mostra feedback imediato → 
Exibe explicação → 
Habilita botão "Próxima"
```

### 3. Navegação
```
Usuário clica "Próxima" → 
Carrega próxima questão → 
Atualiza progresso → 
Repete processo
```

### 4. Finalização
```
Última questão respondida → 
Calcula pontuação final → 
Mostra resultados → 
Permite revisão detalhada
```

## Security Considerations

- **Validação de entrada**: Todas as respostas devem ser validadas
- **Rate limiting**: Limitar frequência de submissões
- **Autorização**: Verificar se usuário pode acessar o quiz
- **Integridade**: Prevenir manipulação de respostas via JavaScript
- **Auditoria**: Log de todas as ações para análise posterior