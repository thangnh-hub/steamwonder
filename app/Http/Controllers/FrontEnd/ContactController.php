<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // dd( $request);
        $request->validate([
            'is_type' => 'required|max:255'
        ]);
        try {
            $params = $request->all();
            $params['status'] = Consts::CONTACT_STATUS['new'];
            $messageResult = '';
            // dd( $params);
            // Case get message
            switch ($params['is_type']) {
                case Consts::CONTACT_TYPE['newsletter']:
                    $messageResult =  __('Subscribe newsletter successfully!');
                    break;
                case Consts::CONTACT_TYPE['advise']:
                    $messageResult =  __('Booking successfull!');
                    break;
                case Consts::CONTACT_TYPE['faq']:
                    $messageResult =  __('Send contact successfully!');
                    break;
                case Consts::CONTACT_TYPE['call_request']:
                    $messageResult =  __('Send call request successfully!');
                    break;
                default:
                    $messageResult =  __('Send contact successfully!');
                    break;
            }
            if ($params['is_type'] == Consts::CONTACT_TYPE['newsletter']) {
                $contact = Contact::firstOrCreate(
                    [
                        'is_type' => $params['is_type'],
                        'email' => $params['email']
                    ]
                );
                session()->flash('successMessage', __('Submitted successfully!'));
                return $this->sendResponse($contact, $messageResult);
            } else {
                $contact = Contact::create($params);
                if (isset($this->responseData['setting']->send_email)) {
                    $email = $this->responseData['setting']->send_email;
                    Mail::send('frontend.emails.contact', ['contact' => $contact], function ($message) use ($email) {
                        $message->to($email);
                        $message->subject(__('You received a new appointment from the system'));
                    });
                }
                session()->flash('successMessage', __('Submitted successfully!'));
                return $this->sendResponse($contact, $messageResult);
            }
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
}
