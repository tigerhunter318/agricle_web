<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Contact;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends BaseController
{
    public function send_contact(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();
        $contact = $request->all();
        $result = Contact::create([
            'user_id' => $user->id,
            'type' => $contact['type'],
            'content' => $contact['content'],
            'name' => $contact['name'],
            'address' => $contact['address']
        ]);

        return $this->sendResponse($result, 'create contact');
    }
}
