<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CaptureAttribution;
use App\Models\Lead;
use Illuminate\Http\Request;
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
            'form' => ['nullable', 'in:contact,audit,landing,quote,ecommerce'],
            'extra' => ['nullable', 'array', 'max:8'],
            'extra.*' => ['nullable', 'string', 'max:300'],
        ], [
            'phone.regex' => 'Please enter a valid 10-digit mobile number.',
        ]);

        $data['phone'] = substr(preg_replace('/\D/', '', $data['phone']), -10);
        // Extra questions from long landing pages are kept in the message, one per line.
        if ($extra = array_filter((array) ($data['extra'] ?? []), fn ($v) => filled($v))) {
            $lines = collect($extra)->map(fn ($v, $k) => mb_substr((string) $k, 0, 60).': '.$v)->implode("\n");
            $data['message'] = trim($lines."\n".($data['message'] ?? ''));
        }
        unset($data['extra']);
        $attr = $request->session()->get('attr', []);

        $lead = Lead::create($data + [
            'form' => $data['form'] ?? 'contact',
            'page_url' => mb_substr((string) $request->headers->get('referer'), 0, 250),
            'ip' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 300),
            'fbp' => mb_substr((string) $request->cookie('_fbp'), 0, 190) ?: null,
        ] + array_intersect_key($attr, array_flip([...CaptureAttribution::KEYS, 'landing_url', 'referrer'])));

        dispatch(fn () => \App\Services\LeadAlerts::dispatch($lead))->afterResponse();

        return redirect()->route('thanks')->with('lead_id', $lead->id);
    }
}
