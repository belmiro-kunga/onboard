<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification->title }}</title>
    <style>
        /* Reset e estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .logo {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .header-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }
        
        .notification-title {
            font-size: 20px;
            font-weight: 600;
            color: #0ea5e9;
            margin-bottom: 15px;
        }
        
        .notification-message {
            font-size: 16px;
            color: #475569;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 30px;
            transition: transform 0.2s ease;
        }
        
        .action-button:hover {
            transform: translateY(-2px);
        }
        
        .footer {
            background-color: #f1f5f9;
            padding: 30px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        
        .footer-links {
            margin-bottom: 20px;
        }
        
        .footer-links a {
            color: #0ea5e9;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .company-info {
            font-size: 12px;
            opacity: 0.8;
        }
        
        /* Responsividade */
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .header, .content, .footer {
                padding: 20px;
            }
            
            .greeting {
                font-size: 20px;
            }
            
            .notification-title {
                font-size: 18px;
            }
            
            .action-button {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">HCP</div>
            <div class="header-subtitle">Hemera Capital Partners</div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">Olá, {{ $user->name }}!</div>
            
            <div class="notification-title">{{ $notification->title }}</div>
            
            <div class="notification-message">
                {{ $notification->message }}
            </div>
            
            @if($actionUrl)
                <a href="{{ $actionUrl }}" class="action-button">
                    Ver Detalhes
                </a>
            @endif
            
            <p style="font-size: 14px; color: #64748b; margin-top: 20px;">
                Esta notificação foi enviada pelo sistema de onboarding da Hemera Capital Partners.
                Se você tiver alguma dúvida, entre em contato conosco.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-links">
                <a href="{{ route('welcome') }}">Início</a>
                <a href="#">Suporte</a>
                <a href="#">Política de Privacidade</a>
            </div>
            
            <div class="company-info">
                © {{ date('Y') }} Hemera Capital Partners. Todos os direitos reservados.<br>
                Este e-mail foi enviado para {{ $user->email }}
            </div>
        </div>
    </div>
</body>
</html> 