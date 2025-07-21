<?php

namespace Tests\Unit\Factories;

use App\Models\User;
use App\Models\Achievement;
use App\Models\Notification;
use App\Models\PointsHistory;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizAttemptAnswer;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    /** @test */
    public function it_can_create_user_with_factory(): void
    {
        $user = User::factory()->make();
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->email);
        $this->assertTrue($user->is_active);
    }

    /** @test */
    public function it_can_create_achievement_with_factory(): void
    {
        $achievement = Achievement::factory()->make();
        
        $this->assertInstanceOf(Achievement::class, $achievement);
        $this->assertNotEmpty($achievement->name);
        $this->assertNotEmpty($achievement->description);
        $this->assertContains($achievement->category, ['learning', 'engagement', 'completion', 'social']);
        $this->assertTrue($achievement->is_active);
    }

    /** @test */
    public function it_can_create_notification_with_factory(): void
    {
        $notification = Notification::factory()->make(['user_id' => 1]);
        
        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertNotEmpty($notification->title);
        $this->assertNotEmpty($notification->message);
        $this->assertContains($notification->type, ['info', 'success', 'warning', 'error']);
        $this->assertNull($notification->read_at);
    }

    /** @test */
    public function it_can_create_points_history_with_factory(): void
    {
        $pointsHistory = PointsHistory::factory()->make(['user_id' => 1]);
        
        $this->assertInstanceOf(PointsHistory::class, $pointsHistory);
        $this->assertGreaterThan(0, $pointsHistory->points);
        $this->assertNotEmpty($pointsHistory->reason);
        $this->assertNotEmpty($pointsHistory->reference_type);
    }

    /** @test */
    public function it_can_create_quiz_question_with_factory(): void
    {
        $question = QuizQuestion::factory()->make(['quiz_id' => 1]);
        
        $this->assertInstanceOf(QuizQuestion::class, $question);
        $this->assertNotEmpty($question->question);
        $this->assertContains($question->question_type, ['multiple_choice', 'single_choice', 'true_false']);
        $this->assertIsArray($question->options);
        $this->assertIsArray($question->correct_answer);
        $this->assertTrue($question->is_active);
    }

    /** @test */
    public function it_can_create_quiz_answer_with_factory(): void
    {
        $answer = QuizAnswer::factory()->make(['quiz_question_id' => 1]);
        
        $this->assertInstanceOf(QuizAnswer::class, $answer);
        $this->assertNotEmpty($answer->answer_text);
        $this->assertIsBool($answer->is_correct);
        $this->assertTrue($answer->is_active);
    }

    /** @test */
    public function it_can_create_quiz_attempt_answer_with_factory(): void
    {
        $attemptAnswer = QuizAttemptAnswer::factory()->make([
            'quiz_attempt_id' => 1,
            'quiz_question_id' => 1
        ]);
        
        $this->assertInstanceOf(QuizAttemptAnswer::class, $attemptAnswer);
        $this->assertNotEmpty($attemptAnswer->selected_answer);
        $this->assertIsBool($attemptAnswer->is_correct);
        $this->assertGreaterThanOrEqual(0, $attemptAnswer->points_earned);
        $this->assertGreaterThan(0, $attemptAnswer->time_spent);
    }

    /** @test */
    public function it_can_create_factory_states(): void
    {
        $adminUser = User::factory()->admin()->make();
        $this->assertEquals('admin', $adminUser->role);

        $inactiveUser = User::factory()->inactive()->make();
        $this->assertFalse($inactiveUser->is_active);

        $rareAchievement = Achievement::factory()->rare()->make();
        $this->assertEquals('rare', $rareAchievement->rarity);

        $readNotification = Notification::factory()->read()->make(['user_id' => 1]);
        $this->assertNotNull($readNotification->read_at);

        $correctAnswer = QuizAnswer::factory()->correct()->make(['quiz_question_id' => 1]);
        $this->assertTrue($correctAnswer->is_correct);
    }
}