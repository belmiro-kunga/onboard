# Estrutura Modular - Sistema de Onboarding HCP

Esta estrutura segue os princípios SOLID para organização modular do código.

## Estrutura dos Módulos

Cada módulo segue a seguinte estrutura:

```
ModuleName/
├── Controllers/     # Controllers específicos do módulo
├── Models/         # Models específicos do módulo
├── Services/       # Lógica de negócio
├── Repositories/   # Abstração de acesso a dados
├── Requests/       # Form Requests para validação
├── Resources/      # API Resources para transformação
├── Events/         # Events específicos do módulo
├── Listeners/      # Event Listeners
├── Jobs/          # Jobs assíncronos
├── Policies/      # Authorization Policies
├── Providers/     # Service Providers do módulo
└── Routes/        # Rotas específicas do módulo
```

## Módulos Planejados

1. **Auth** - Autenticação e autorização
2. **User** - Gestão de usuários
3. **Module** - Módulos de conteúdo
4. **Quiz** - Sistema de quizzes
5. **Gamification** - Pontos, medalhas, rankings
6. **Progress** - Acompanhamento de progresso
7. **Notification** - Sistema de notificações
8. **Admin** - Painel administrativo
9. **Resource** - Biblioteca de recursos
10. **Social** - Funcionalidades sociais

## Princípios SOLID Aplicados

### Single Responsibility Principle (SRP)
- Cada classe tem uma única responsabilidade
- Services focados em lógica de negócio específica
- Repositories focados apenas em acesso a dados

### Open/Closed Principle (OCP)
- Classes abertas para extensão, fechadas para modificação
- Uso de interfaces para contratos
- Strategy pattern para diferentes implementações

### Liskov Substitution Principle (LSP)
- Subclasses podem substituir classes base
- Interfaces bem definidas

### Interface Segregation Principle (ISP)
- Interfaces específicas e focadas
- Clientes não dependem de métodos que não usam

### Dependency Inversion Principle (DIP)
- Dependência de abstrações, não de implementações
- Injeção de dependência via Service Container