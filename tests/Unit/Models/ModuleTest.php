<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\Quiz;
use App\Models\UserProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    private Module $module;

    protected function setUp(): void
    {
        parent::setUp();
        $this->module = Module::factory()->create([
            'title' => 'Introdução à HCP',
            'description' => 'Conheça nossa história, valores e missão',
            'category' => 'culture',
            'order_index' => 1,
            'is_active' => true,
            'points_reward' => 100,
            'estimated_duration' => 30,
            'content_type' => 'video',
            'difficulty_level' => 'basic',
        ]);
    }

    /** @test */
    public function it_can_create_module_with_valid_data()
    {
        $moduleData = [
            'title' => 'Políticas de Segurança',
            'description' => 'Aprenda sobre nossas políticas de segurança',
            'category' => 'security',
            'order_index' => 2,
            'is_active' => true,
            'points_reward' => 150,
            'estimated_duration' => 45,
            'content_type' => 'interactive',
            'difficulty_level' => 'intermediate',
        ];

        $module = Module::create($moduleData);

        $this->assertInstanceOf(Module::class, $module);
        $this->assertEquals('Políticas de Segurança', $module->title);
        $this->assertEquals('security', $module->category);
        $this->assertEquals(150, $module->points_reward);
        $this->assertEquals('intermediate', $module->difficulty_level);
    }

    /** @test */
    public function it_has_contents_relationship()
    {
        ModuleContent::factory()->create(['module_id' => $this->module->id]);

        $this->assertInstanceOf(Collection::class, $this->module->contents);
        $this->assertCount(1, $this->module->contents);
    }

    /** @test */
    public function it_has_quizzes_relationship()
    {
        Quiz::factory()->create(['module_id' => $this->module->id]);

        $this->assertInstanceOf(Collection::class, $this->module->quizzes);
        $this->assertCount(1, $this->module->quizzes);
    }

    /** @test */
    public function it_has_user_progress_relationship()
    {
        UserProgress::factory()->create(['module_id' => $this->module->id]);

        $this->assertInstanceOf(Collection::class, $this->module->userProgress);
        $this->assertCount(1, $this->module->userProgress);
    }

    /** @test */
    public function it_can_get_active_modules()
    {
        Module::factory()->create(['is_active' => false]);
        Module::factory()->create(['is_active' => true]);

        $activeModules = Module::active()->get();

        $this->assertCount(2, $activeModules); // 1 do setUp + 1 criado
        $this->assertTrue($activeModules->every(fn($module) => $module->is_active));
    }

    /** @test */
    public function it_can_get_modules_by_category()
    {
        Module::factory()->create(['category' => 'hr']);
        Module::factory()->create(['category' => 'it']);

        $cultureModules = Module::byCategory('culture')->get();
        $hrModules = Module::byCategory('hr')->get();

        $this->assertCount(1, $cultureModules); // Apenas o do setUp
        $this->assertCount(1, $hrModules);
    }

    /** @test */
    public function it_can_get_modules_by_difficulty()
    {
        Module::factory()->create(['difficulty_level' => 'intermediate']);
        Module::factory()->create(['difficulty_level' => 'advanced']);

        $basicModules = Module::byDifficulty('basic')->get();
        $intermediateModules = Module::byDifficulty('intermediate')->get();

        $this->assertCount(1, $basicModules); // Apenas o do setUp
        $this->assertCount(1, $intermediateModules);
    }

    /** @test */
    public function it_can_get_modules_in_order()
    {
        Module::factory()->create(['order_index' => 3]);
        Module::factory()->create(['order_index' => 2]);

        $orderedModules = Module::ordered()->get();

        $this->assertEquals(1, $orderedModules->first()->order_index);
        $this->assertEquals(3, $orderedModules->last()->order_index);
    }

    /** @test */
    public function it_can_check_if_module_is_completed_by_user()
    {
        $user = \App\Models\User::factory()->create();
        
        $this->assertFalse($this->module->isCompletedBy($user));

        UserProgress::factory()->create([
            'user_id' => $user->id,
            'module_id' => $this->module->id,
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $this->assertTrue($this->module->isCompletedBy($user));
    }

    /** @test */
    public function it_can_get_completion_percentage_for_user()
    {
        $user = \App\Models\User::factory()->create();
        
        $percentage = $this->module->getCompletionPercentageFor($user);
        $this->assertEquals(0, $percentage);

        // Simular progresso de 50%
        UserProgress::factory()->create([
            'user_id' => $user->id,
            'module_id' => $this->module->id,
            'progress_percentage' => 50,
        ]);

        $percentage = $this->module->getCompletionPercentageFor($user);
        $this->assertEquals(50, $percentage);
    }

    /** @test */
    public function it_can_get_estimated_duration_formatted()
    {
        $this->module->update(['estimated_duration' => 90]);
        
        $formatted = $this->module->formatted_duration;
        
        $this->assertEquals('1h 30min', $formatted);
    }

    /** @test */
    public function it_can_get_content_data_as_array()
    {
        $contentData = [
            'video_url' => 'https://example.com/video.mp4',
            'transcript' => 'Transcrição do vídeo...',
            'markers' => [
                ['time' => 30, 'title' => 'Introdução'],
                ['time' => 120, 'title' => 'Valores da empresa'],
            ]
        ];

        $this->module->update(['content_data' => $contentData]);

        $this->assertIsArray($this->module->content_data);
        $this->assertEquals('https://example.com/video.mp4', $this->module->content_data['video_url']);
        $this->assertCount(2, $this->module->content_data['markers']);
    }

    /** @test */
    public function it_can_get_prerequisites_as_array()
    {
        $prerequisites = [
            'modules' => [1, 2, 3],
            'min_level' => 2,
            'required_points' => 500
        ];

        $this->module->update(['prerequisites' => $prerequisites]);

        $this->assertIsArray($this->module->prerequisites);
        $this->assertEquals([1, 2, 3], $this->module->prerequisites['modules']);
        $this->assertEquals(2, $this->module->prerequisites['min_level']);
    }
} 