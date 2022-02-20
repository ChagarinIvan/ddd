<?php

namespace App\Http\Controllers;

use App\Exceptions\MalformedJsonException;
use App\Models\Verification;
use App\Services\VerificationService;
use App\Verifications\Subject;
use App\Verifications\SubjectType;
use App\Verifications\UserInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class VerificationController extends Controller
{
    public function __construct(
        private readonly VerificationService $service,
        private readonly ValidatorFactory $validator,
    ) {}

    public function store(Request $request): Response
    {
        try {
            $verification = $this->service->storeVerification(
                $this->extractSubjectFromRequest($request),
                $this->getUserInfo($request),
            );

            return response()->json($verification->id, Response::HTTP_CREATED);
        } catch (MalformedJsonException) {
            return response('', Response::HTTP_BAD_REQUEST);
        } catch (ValidationException) {
            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function confirm(string $id, Request $request): JsonResponse
    {
        $verification = Verification::find($id);
        $userInfo = $this->getUserInfo($request);
        $code = (int)$request->get('code');
        $this->service->confirmVerification($verification, $code, $userInfo);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws MalformedJsonException
     * @throws ValidationException
     */
    private function extractSubjectFromRequest(Request $request): Subject
    {
        $subjectData = $request->json()->all();
        if ($subjectData === []) {
            throw new MalformedJsonException();
        }

        $subjectData = $this->validator->make($subjectData, [
            'subject.identity' => 'required',
            'subject.type' => [new Enum(SubjectType::class), 'required'],
        ])->validate();

        return new Subject(
            $subjectData['subject']['identity'],
            SubjectType::from($subjectData['subject']['type'])
        );
    }

    private function getUserInfo(Request $request): UserInfo
    {
        return new UserInfo(
            $request->getClientIp(),
            $request->userAgent()
        );
    }
}
