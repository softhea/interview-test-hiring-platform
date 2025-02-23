<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\NotifyCandidate;
use App\Models\Company;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactCandidateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_contact_candidate_works_as_expected()
    {
        Mail::fake();

        $response = $this->patchJson('/candidates/1/contact');

        $response->assertOk();
        $response->assertExactJson([
            'message' => 'Candidate has been contacted',
            'coins' => 15,
        ]);

        $company = Company::find(1);
        $this->assertEquals(15, $company->getCoins());

        $notification = $company->getLatestContactNotificationToUserId(2);
        $this->assertNotNull($notification);

        Mail::assertSent(function (NotifyCandidate $mail): bool {
            return 
                $mail->notification->getSender()->getUserId() === 1
                && $mail->notification->getReceiver()->getUserId() === 2
                && $mail->notification->getTypeId() === 1;
        });
    }

    public function test_contact_candidate_fails_when_company_doesnt_have_enough_coins()
    {
        /**
         * @var Company $company
         */
        $company = Company::find(1);
        $company->decreaseCoins(16);

        $response = $this->patchJson('/candidates/1/contact');

        $response->assertStatus(400);
        $response->assertExactJson([
            'error' => 'To be able to contact candidate you need 5 coins and you only have 4!',
        ]);
    }
}
