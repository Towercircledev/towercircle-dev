<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CaptureAttribution;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        // Bots fill the hidden "company_url" field or submit within 2 seconds of the page loading.
        $tooFast = (int) $request->input('_t') > 0 && (time() - (int) $request->input('_t')) < 2;
        if ($request->filled('company_url') || $tooFast) {
            return redirect()->route('thanks');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^(\+?91[\s-]?)?[6-9]\d{4}[\s-]?\d{5}$/'],
            'email' => ['nullable', 'email', 'max:190'],
            'company' => ['nullable', 'string', 'max:150'],
            'website' => ['nullable', 'string', 'max:250'],
            'service' => ['nullable', 'string', 'max:150'],
            'budget' => ['nullable', Rule::in(array_keys(Lead::BUDGETS))],
            'timeline' => ['nullable', Rule::in(array_keys(Lead::TIMELINES))],
            'message' => ['nullable', 'string', 'max:3000'],
            'form' => ['nullable', 'in:contact,audit,landing,quote'],
        ], [
            'phone.regex' => 'Please enter a valid 10-digit mobile number.',
        ]);

        $data['phone'] = substr(preg_replace('/\D/', '', $data['phone']), -10);
        $attr = $request->session()->get('attr', []);

        $lead = Lead::create($data + [
            'form' => $data['form'] ?? 'contact',
            'page_url' => mb_substr((string) $request->headers->get('referer'), 0, 250),
            'ip' => $request->ip(),
        ] + array_intersect_key($attr, array_flip([...CaptureAttribution::KEYS, 'landing_url', 'referrer'])));

        $this->notify($lead);

        return redirect()->route('thanks')->with('lead_id', $lead->id);
    }

    private function notify(Lead $lead): void
    {
        $to = array_filter(array_map('trim', explode(',', (string) site('lead_emails', site('email')))));
        if (! $to) {
            return;
        }
        $budget = Lead::BUDGETS[$lead->budget] ?? '—';
        $body = "New enquiry from the website\n\n"
            ."Name: {$lead->name}\nPhone: {$lead->phone}\nEmail: ".($lead->email ?: '—')."\n"
            .'Service: '.($lead->service ?: '—')."\nBudget: {$budget}\nWebsite: ".($lead->website ?: '—')."\n\n"
            .($lead->message ?: '')."\n\nSource: ".$lead->sourceLabel()."\n"
            .'WhatsApp: https://wa.me/91'.$lead->phone."\n"
            .'Open in admin: '.url('/admin/leads/'.$lead->id.'/edit');

        try {
            Mail::raw($body, fn ($m) => $m->to($to)->subject('New lead: '.$lead->name.($lead->service ? ' – '.$lead->service : '')));
        } catch (\Throwable $e) {
            Log::warning('Lead email failed: '.$e->getMessage());
        }
    }
}
