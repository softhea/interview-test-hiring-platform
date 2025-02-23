<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use App\Notifications\ContactNotification;
use App\Services\HiringService;
use App\Services\NotificationService;
use Exception;
use Illuminate\Http\JsonResponse;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with('receivedNotifications')->get();
        $company = Company::with('wallet')->find(1);
        $coins = $company->getCoins();

        $desiredSoftSkills = [];
        foreach ($candidates as $candidate) {
            $softSkills = json_decode($candidate->soft_skills, true);
            foreach ($softSkills as $softSkill) {
                $desiredSoftSkills[] = $softSkill;
            }
            $candidate->can_be_hired = 
                !$candidate->isHired() 
                && $candidate->hasBeenContactedByUserIdBefore($company->getUserId());
        }
        $desiredSoftSkills = array_unique($desiredSoftSkills);
        $desiredSoftSkills = array_slice($desiredSoftSkills, 0, 2);

        return view(
            'candidates.index', 
            compact( 
                'candidates', 
                'coins',
                'desiredSoftSkills'
            )
        );
    }

    public function contact(Candidate $candidate, NotificationService $notificationService): JsonResponse
    {
        /** @var Company $company */
        $company = Company::query()->with('wallet')->find(1);

        try {
            $notificationService->notify(notification: new ContactNotification(
                $company, 
                $candidate
            ));
        } catch (Exception $exception) {
            return new JsonResponse(
                [
                    'error' => $exception->getMessage(),
                ],
                400
            );
        }
        
        return new JsonResponse([
            'message' => 'Candidate has been contacted',
            'coins' => $company->refresh()->getCoins(),
        ]);
    }

    public function hire(Candidate $candidate, HiringService $hiringService)
    {
        /** @var Company $company */
        $company = Company::query()->with('wallet')->find(1);

        try {
            $hiringService->hire( $company, $candidate);
        } catch (Exception $exception) {
            return new JsonResponse(
                [
                    'error' => $exception->getMessage(),
                ],
                400
            );
        }
        
        return new JsonResponse([
            'message' => 'Candidate has been hired',
            'coins' => $company->refresh()->getCoins(),
        ]);
    }
}
