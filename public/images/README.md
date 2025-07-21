# Imagens do Sistema HCP

Este diretório contém as imagens utilizadas no sistema de onboarding da Hemera Capital Partners.

## Imagens Necessárias

### Para a Página de Boas-vindas (welcome.blade.php)

1. **hcp-building.jpg** - Imagem do edifício corporativo HCP
   - Dimensões recomendadas: 1920x1080px
   - Formato: JPEG
   - Uso: Background do hero section
   - Descrição: Edifício moderno da Hemera Capital Partners

2. **hcp-logo.png** - Logo oficial da HCP
   - Dimensões recomendadas: 200x200px
   - Formato: PNG com transparência
   - Uso: Logo no card principal
   - Descrição: Logo corporativo da Hemera Capital Partners

3. **hcp-onboarding-preview.jpg** - Preview para redes sociais
   - Dimensões recomendadas: 1200x630px
   - Formato: JPEG
   - Uso: Open Graph e Twitter Cards
   - Descrição: Preview do sistema de onboarding

### Para Outras Páginas

4. **hcp-avatar-default.png** - Avatar padrão para usuários
   - Dimensões: 100x100px
   - Formato: PNG
   - Uso: Avatar padrão quando usuário não tem foto

5. **achievement-icons/** - Ícones para conquistas
   - Dimensões: 64x64px
   - Formato: SVG ou PNG
   - Uso: Sistema de gamificação

## Otimizações Recomendadas

- **Compressão**: Use ferramentas como TinyPNG ou ImageOptim
- **Formatos**: Use WebP para melhor performance (com fallback para JPEG/PNG)
- **Lazy Loading**: Todas as imagens devem suportar lazy loading
- **Responsive**: Considere diferentes tamanhos para mobile/desktop

## Estrutura de Diretórios Sugerida

```
public/images/
├── logos/
│   ├── hcp-logo.png
│   └── hcp-logo-white.png
├── backgrounds/
│   ├── hcp-building.jpg
│   └── hcp-building-mobile.jpg
├── avatars/
│   └── default.png
├── achievements/
│   ├── badge-1.svg
│   ├── badge-2.svg
│   └── ...
├── social/
│   └── hcp-onboarding-preview.jpg
└── README.md
```

## Notas de Implementação

- As imagens são referenciadas no código usando `{{ asset('images/filename.jpg') }}`
- Para lazy loading, use `loading="lazy"` nos elementos `<img>`
- Para WebP, considere usar `<picture>` com fallback
- Sempre inclua atributos `alt` descritivos para acessibilidade