# Vídeos do Sistema HCP

Este diretório contém os vídeos utilizados no sistema de onboarding da Hemera Capital Partners.

## Vídeos Necessários

### Para a Página de Boas-vindas (welcome.blade.php)

1. **hcp-hero-background.mp4** - Vídeo de background do hero section
   - Dimensões recomendadas: 1920x1080px (Full HD)
   - Duração: 10-30 segundos (loop)
   - Formato: MP4 (H.264)
   - Tamanho máximo: 10MB
   - Uso: Background do hero section em loop
   - Descrição: Vídeo corporativo da HCP com movimento sutil

2. **hcp-hero-background.webm** - Versão WebM do vídeo de background
   - Dimensões: 1920x1080px
   - Formato: WebM (VP9)
   - Tamanho máximo: 8MB
   - Uso: Fallback para navegadores modernos (melhor compressão)

### Para Outras Páginas

3. **hcp-intro.mp4** - Vídeo de introdução
   - Dimensões: 1280x720px
   - Duração: 2-5 minutos
   - Formato: MP4
   - Uso: Vídeo de boas-vindas para novos colaboradores

4. **hcp-tutorial.mp4** - Vídeo tutorial do sistema
   - Dimensões: 1280x720px
   - Duração: 5-15 minutos
   - Formato: MP4
   - Uso: Tutorial de como usar o sistema

## Especificações Técnicas

### Hero Background Video
- **Resolução**: 1920x1080 (Full HD)
- **Frame Rate**: 24-30 fps
- **Codec**: H.264 (MP4) / VP9 (WebM)
- **Bitrate**: 2-5 Mbps
- **Duração**: 10-30 segundos (loop infinito)
- **Conteúdo**: Movimento sutil, sem texto ou elementos que distraiam
- **Tema**: Corporativo, profissional, cores da HCP

### Otimizações Recomendadas

- **Compressão**: Use ferramentas como HandBrake ou FFmpeg
- **Formatos**: Forneça MP4 e WebM para melhor compatibilidade
- **Lazy Loading**: Vídeos são carregados sob demanda
- **Mobile**: Versões otimizadas para dispositivos móveis
- **Acessibilidade**: Incluir legendas quando necessário

## Estrutura de Diretórios Sugerida

```
public/videos/
├── hero/
│   ├── hcp-hero-background.mp4
│   ├── hcp-hero-background.webm
│   └── hcp-hero-background-mobile.mp4
├── tutorials/
│   ├── hcp-intro.mp4
│   ├── hcp-tutorial.mp4
│   └── hcp-walkthrough.mp4
├── modules/
│   ├── module-1-intro.mp4
│   ├── module-2-content.mp4
│   └── ...
└── README.md
```

## Notas de Implementação

- Os vídeos são referenciados no código usando `{{ asset('videos/filename.mp4') }}`
- Para lazy loading, use `loading="lazy"` nos elementos `<video>`
- Para WebM, considere usar `<source>` com fallback para MP4
- Sempre inclua atributos `alt` descritivos para acessibilidade
- Implementar fallback para imagem quando vídeo não carrega

## Comandos FFmpeg para Otimização

```bash
# Converter para MP4 otimizado
ffmpeg -i input.mp4 -c:v libx264 -crf 23 -preset medium -c:a aac -b:a 128k output.mp4

# Converter para WebM
ffmpeg -i input.mp4 -c:v libvpx-vp9 -crf 30 -b:v 0 -c:a libopus output.webm

# Criar versão mobile (720p)
ffmpeg -i input.mp4 -vf scale=1280:720 -c:v libx264 -crf 25 output-mobile.mp4
```

## Performance

- **Hero video**: Máximo 10MB para carregamento rápido
- **Tutorial videos**: Máximo 50MB por vídeo
- **Streaming**: Implementar streaming adaptativo se necessário
- **CDN**: Considerar uso de CDN para vídeos grandes 