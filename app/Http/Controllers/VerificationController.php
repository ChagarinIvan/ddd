<?php

namespace App\Http\Controllers;

use App\Exceptions\DublicatedVerificationException;
use App\Exceptions\MalformedJsonException;
use App\Models\Verification;
use App\Services\VerificationService;
use App\Verifications\Subject;
use App\Verifications\SubjectType;
use App\Verifications\UserInfo;
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
        } catch (DublicatedVerificationException) {
            return response('', Response::HTTP_CONFLICT);
        } catch (MalformedJsonException) {
            return response('', Response::HTTP_BAD_REQUEST);
        } catch (ValidationException) {
            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function confirm(string $id, Request $request): Response
    {
        $verification = Verification::find($id);
        if ($verification === null) {
            return response('', Response::HTTP_NOT_FOUND);
        }
        if ($verification->isExpire) {
            return response('', Response::HTTP_GONE);
        }
        if (!$verification->userInfo->equalTo($this->getUserInfo($request))) {
            return response('', Response::HTTP_FORBIDDEN);
        }
        if ($verification->confirmed) {
            return response('', Response::HTTP_NO_CONTENT);
        }

        try {
            $code = $this->getCode($request);
            if ($this->service->confirmVerification($verification, $code)) {
                return response('', Response::HTTP_NO_CONTENT);
            }

            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (MalformedJsonException) {
            return response('', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @throws MalformedJsonException
     * @throws ValidationException
     */
    private function extractSubjectFromRequest(Request $request): Subject
    {
        $json = $request->json();
        if ($json === null || ($subjectData = $json->all()) === []) {
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

    /**
     * @throws MalformedJsonException
     */
    private function getCode(Request $request): int
    {
        $json = $request->json();
        if ($json === null || ($codeData = $json->all()) === [] || !array_key_exists('code', $codeData)) {
            throw new MalformedJsonException();
        }

        return (int)$codeData['code'];
    }

    private function getUserInfo(Request $request): UserInfo
    {
        return new UserInfo(
            $request->getClientIp(),
            $request->userAgent()
        );
    }
}
