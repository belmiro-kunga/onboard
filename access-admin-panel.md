# Instruções para Acessar o Painel Administrativo HCP

## Passo 1: Criar um Usuário Administrador

Execute o script `admin-access.php` para verificar se existe um usuário administrador e, se necessário, criar um novo:

```bash
php admin-access.php
```

Este script irá:
1. Verificar se já existe um usuário com a role 'admin'
2. Se não existir, criar um novo usuário administrador com as seguintes credenciais:
   - Email: admin@hcp.com
   - Senha: admin123

## Passo 2: Iniciar o Servidor de Desenvolvimento

Se você ainda não iniciou o servidor de desenvolvimento, execute:

```bash
php artisan serve
```

## Passo 3: Acessar o Painel Administrativo

1. Abra seu navegador e acesse: http://localhost:8000/login
2. Faça login com as credenciais do administrador:
   - Email: admin@hcp.com
   - Senha: admin123
3. Após o login, acesse: http://localhost:8000/admin

## Funcionalidades Disponíveis no Painel Administrativo

O painel administrativo oferece as seguintes funcionalidades:

1. **Dashboard**: Visão geral do sistema com estatísticas e métricas
2. **Gerenciamento de Usuários**: Criar, editar e gerenciar usuários
3. **Gerenciamento de Quizzes**: Criar e gerenciar quizzes e questões
4. **Relatórios**: Gerar e exportar relatórios de uso e desempenho

## Solução de Problemas

Se você encontrar algum problema ao acessar o painel administrativo:

1. **Erro 403 (Forbidden)**: Verifique se o usuário tem a role 'admin'
2. **Erro de Login**: Verifique se as credenciais estão corretas
3. **Erro de Middleware**: Verifique se o middleware 'admin' está registrado no Kernel HTTP

Para verificar ou alterar a role de um usuário existente, você pode usar o Tinker:

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'seu-email@exemplo.com')->first();
$user->role = 'admin';
$user->save();
```

## Segurança

Lembre-se de alterar a senha do usuário administrador em um ambiente de produção!