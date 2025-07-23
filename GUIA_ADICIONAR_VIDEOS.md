# 🎥 GUIA: COMO ADICIONAR VÍDEOS NOS CURSOS

## 📋 VISÃO GERAL

O sistema oferece **3 tipos de vídeos** que podem ser adicionados aos cursos:
- **YouTube**: Links de vídeos do YouTube
- **Local**: Upload de arquivos de vídeo
- **Vimeo**: Links de vídeos do Vimeo

## 🚀 MÉTODO 1: VIA INTERFACE ADMINISTRATIVA

### Passo 1: Acessar o Curso
```
http://127.0.0.1:8000/admin/courses
```
- Encontre o curso desejado
- Clique em "Ver" ou "Editar"

### Passo 2: Gerenciar Módulos do Curso
```
http://127.0.0.1:8000/admin/courses/{id}/modules
```
- Clique em "Módulos" no curso
- Ou acesse diretamente a URL acima

### Passo 3: Criar/Editar Módulo
- Clique em "Novo Módulo" ou edite um existente
- Preencha:
  - **Título**: Nome do módulo
  - **Descrição**: Descrição detalhada
  - **Duração**: Tempo estimado em minutos
  - **Dificuldade**: Iniciante, Intermediário, Avançado

### Passo 4: Adicionar Aulas com Vídeos
- Dentro do módulo, clique em "Nova Aula"
- Preencha os dados da aula
- **Configure o vídeo**:

#### 🎬 Para Vídeo do YouTube:
```
Tipo: YouTube
URL: https://www.youtube.com/watch?v=VIDEO_ID
```

#### 📁 Para Vídeo Local:
```
Tipo: Local
Arquivo: Selecione o arquivo .mp4, .avi, .mov, .wmv
Tamanho máximo: 1GB
```

#### 🎭 Para Vídeo do Vimeo:
```
Tipo: Vimeo  
URL: https://vimeo.com/VIDEO_ID
```

## 🛠️ MÉTODO 2: VIA CÓDIGO/PROGRAMAÇÃO

### Criar Aula com Vídeo Programaticamente

```php
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\LessonVideo;

// 1. Encontrar o curso
$course = Course::find(1);

// 2. Criar ou encontrar módulo
$module = $course->modules()->create([
    'title' => 'Módulo de Vídeos',
    'description' => 'Módulo com aulas em vídeo',
    'order_index' => 1,
    'is_active' => true,
    'duration_minutes' => 120,
    'difficulty_level' => 'beginner',
]);

// 3. Criar aula
$lesson = $module->lessons()->create([
    'title' => 'Aula 1: Introdução',
    'description' => 'Primeira aula do curso',
    'order_index' => 1,
    'is_active' => true,
    'duration_minutes' => 30,
]);

// 4. Adicionar vídeo à aula
$lesson->video()->create([
    'type' => 'youtube', // ou 'local' ou 'vimeo'
    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    'video_id' => 'dQw4w9WgXcQ',
    'duration_seconds' => 1800, // 30 minutos
    'auto_play_next' => true,
    'picture_in_picture' => true,
]);
```

## 📊 MÉTODO 3: VIA SEEDER (Para Dados de Teste)

### Criar Seeder para Vídeos

```php
// database/seeders/CourseVideosSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;

class CourseVideosSeeder extends Seeder
{
    public function run()
    {
        $course = Course::find(1); // Substitua pelo ID do seu curso
        
        // Módulo 1: Introdução
        $module1 = $course->modules()->create([
            'title' => 'Introdução ao Curso',
            'description' => 'Módulo introdutório com vídeos explicativos',
            'order_index' => 1,
            'is_active' => true,
            'duration_minutes' => 90,
            'difficulty_level' => 'beginner',
        ]);

        // Aula 1.1: Vídeo do YouTube
        $lesson1 = $module1->lessons()->create([
            'title' => 'Bem-vindos ao Curso',
            'description' => 'Vídeo de boas-vindas e apresentação',
            'order_index' => 1,
            'is_active' => true,
            'duration_minutes' => 15,
        ]);

        $lesson1->video()->create([
            'type' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=EXEMPLO',
            'video_id' => 'EXEMPLO',
            'duration_seconds' => 900,
            'auto_play_next' => true,
        ]);

        // Aula 1.2: Vídeo Local (exemplo)
        $lesson2 = $module1->lessons()->create([
            'title' => 'Conceitos Fundamentais',
            'description' => 'Explicação dos conceitos básicos',
            'order_index' => 2,
            'is_active' => true,
            'duration_minutes' => 25,
        ]);

        $lesson2->video()->create([
            'type' => 'local',
            'file_path' => 'videos/conceitos-fundamentais.mp4',
            'duration_seconds' => 1500,
            'file_size' => 52428800, // 50MB
        ]);
    }
}
```

## 🎯 FUNCIONALIDADES AVANÇADAS

### 1. **Adicionar Legendas**
```php
$video = LessonVideo::find(1);
$video->addSubtitle('pt', 'legendas/aula1-pt.vtt', 'Português');
$video->addSubtitle('en', 'legendas/aula1-en.vtt', 'English');
```

### 2. **Adicionar Capítulos**
```php
$video->addChapter(0, 'Introdução', 'Apresentação do tema');
$video->addChapter(300, 'Desenvolvimento', 'Explicação detalhada');
$video->addChapter(600, 'Conclusão', 'Resumo e próximos passos');
```

### 3. **Adicionar Materiais Complementares**
```php
$lesson->materials()->create([
    'title' => 'Slides da Aula',
    'type' => 'pdf',
    'file_path' => 'materials/slides-aula1.pdf',
    'is_downloadable' => true,
    'order_index' => 1,
]);
```

### 4. **Adicionar Quiz após o Vídeo**
```php
$lesson->quiz()->create([
    'title' => 'Quiz da Aula 1',
    'type' => 'quiz',
    'questions' => [
        [
            'id' => uniqid(),
            'type' => 'multiple_choice',
            'question' => 'Qual o conceito principal da aula?',
            'options' => ['A', 'B', 'C', 'D'],
            'correct_answer' => 'A',
        ]
    ],
    'passing_score' => 70,
    'is_required' => true,
]);
```

## 📱 INTERFACE DO USUÁRIO

### Como os Usuários Verão os Vídeos

```
http://127.0.0.1:8000/lessons/{lesson_id}
```

**Funcionalidades disponíveis:**
- ✅ Player de vídeo responsivo
- ✅ Controles de velocidade
- ✅ Picture-in-picture
- ✅ Legendas (se disponíveis)
- ✅ Capítulos para navegação
- ✅ Progresso automático
- ✅ Próxima aula automática
- ✅ Comentários e discussões
- ✅ Notas pessoais com timestamp
- ✅ Materiais complementares
- ✅ Quiz após o vídeo

## 🔧 CONFIGURAÇÕES AVANÇADAS

### 1. **Configurar Upload de Vídeos**

No arquivo `config/filesystems.php`:
```php
'disks' => [
    'videos' => [
        'driver' => 'local',
        'root' => storage_path('app/public/videos'),
        'url' => env('APP_URL').'/storage/videos',
        'visibility' => 'public',
    ],
],
```

### 2. **Configurar Validação de Upload**

No `LessonRequest.php`:
```php
'video_file' => 'required_if:video_type,local|nullable|file|mimes:mp4,avi,mov,wmv|max:1048576', // 1GB
```

### 3. **Configurar Compressão Automática**

```php
// No controller, após upload
if ($request->video_type === 'local' && $request->hasFile('video_file')) {
    $videoPath = $request->file('video_file')->store('videos', 'public');
    
    // Processar vídeo (opcional)
    dispatch(new ProcessVideoJob($videoPath));
}
```

## 🚀 EXEMPLO PRÁTICO COMPLETO

### Criar Curso com Vídeos do Zero

```php
// 1. Criar curso
$course = Course::create([
    'title' => 'Curso de Laravel Avançado',
    'description' => 'Aprenda Laravel do básico ao avançado',
    'is_active' => true,
]);

// 2. Criar módulo
$module = $course->modules()->create([
    'title' => 'Fundamentos do Laravel',
    'description' => 'Conceitos básicos e configuração',
    'order_index' => 1,
    'is_active' => true,
    'duration_minutes' => 180,
    'difficulty_level' => 'beginner',
]);

// 3. Criar aulas com vídeos
$aulas = [
    [
        'title' => 'Instalação e Configuração',
        'video_url' => 'https://www.youtube.com/watch?v=EXEMPLO1',
        'duration' => 30,
    ],
    [
        'title' => 'Rotas e Controllers',
        'video_url' => 'https://www.youtube.com/watch?v=EXEMPLO2',
        'duration' => 45,
    ],
    [
        'title' => 'Models e Migrations',
        'video_url' => 'https://www.youtube.com/watch?v=EXEMPLO3',
        'duration' => 60,
    ],
];

foreach ($aulas as $index => $aulaData) {
    $lesson = $module->lessons()->create([
        'title' => $aulaData['title'],
        'order_index' => $index + 1,
        'is_active' => true,
        'duration_minutes' => $aulaData['duration'],
    ]);

    $lesson->video()->create([
        'type' => 'youtube',
        'video_url' => $aulaData['video_url'],
        'video_id' => $this->extractYouTubeId($aulaData['video_url']),
        'duration_seconds' => $aulaData['duration'] * 60,
        'auto_play_next' => true,
    ]);
}
```

## ✅ CHECKLIST PARA ADICIONAR VÍDEOS

- [ ] Curso criado e ativo
- [ ] Módulo criado no curso
- [ ] Aula criada no módulo
- [ ] Vídeo configurado na aula
- [ ] Tipo de vídeo definido (YouTube/Local/Vimeo)
- [ ] URL ou arquivo fornecido
- [ ] Duração configurada
- [ ] Thumbnail gerada/configurada
- [ ] Legendas adicionadas (opcional)
- [ ] Capítulos configurados (opcional)
- [ ] Materiais complementares (opcional)
- [ ] Quiz pós-vídeo (opcional)
- [ ] Testado na interface do usuário

## 🎊 RESULTADO FINAL

Após seguir este guia, você terá:
- ✅ **Cursos com vídeos** funcionais
- ✅ **Player avançado** com todos os recursos
- ✅ **Progresso automático** dos usuários
- ✅ **Estatísticas detalhadas** de visualização
- ✅ **Experiência completa** de aprendizado online

**🚀 SISTEMA DE VÍDEOS PRONTO PARA USO!**