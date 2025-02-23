<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\NotifyCandidate;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HireCandidateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_hire_candidate_works_as_expected()
    {
        Mail::fake();
        
        Notification::query()->create([
            Notification::TYPE_ID => 1,
            Notification::SENDER_USER_ID => 1,
            Notification::RECEIVER_USER_ID => 2,
        ]);

        $response = $this->patchJson('/candidates/1/hire');
        
        $response->assertOk();
        $response->assertExactJson([
            'message' => 'Candidate has been hired',
            'coins' => 25,
        ]);

        $company = Company::find(1);
        $this->assertEquals(25, $company->getCoins());

        /** @var Candidate $candidate */
        $candidate = Candidate::find(1);
        $this->assertTrue($candidate->isHired());

        $notification = Notification::query()
            ->where(Notification::SENDER_USER_ID, 1)
            ->where(Notification::RECEIVER_USER_ID, 2)
            ->where(Notification::TYPE_ID, 2)
            ->first();
        $this->assertNotNull($notification);

        Mail::assertSent(function (NotifyCandidate $mail): bool {
            return 
                $mail->notification->getSender()->getUserId() === 1
                && $mail->notification->getReceiver()->getUserId() === 2
                && $mail->notification->getTypeId() === 2;
        });
    }

    public function test_hire_candidate_fails_when_candidate_is_already_hired()
    {
        /** @var Candidate $candidate */
        $candidate = Candidate::find(1);
        $candidate->hire();

        $response = $this->patchJson('/candidates/1/hire');

        $response->assertStatus(400);
        $response->assertExactJson([
            'error' => 'Candidate is already hired!',
        ]);
    }

    public function test_hire_candidate_fails_when_candidate_hasnt_been_contacted()
    {
        $response = $this->patchJson('/candidates/1/hire');

        $response->assertStatus(400);
        $response->assertExactJson([
            'error' => 'Employer hasn\'t contacted the Candidate before!',
        ]);
    }
}
