<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Setting;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoicePdf
{
    public static function render(Payment $payment): string
    {
        $inv = Setting::get('invoice', []);
        $sameState = ! empty($inv['state']) && $payment->client->gstin
            ? substr($payment->client->gstin, 0, 2) === substr((string) ($inv['gstin'] ?? ''), 0, 2)
            : true;

        $html = view('pdf.invoice', [
            'p' => $payment->loadMissing('client'),
            'inv' => $inv,
            'sameState' => $sameState,
            'logo' => is_file(public_path('img/logo.png')) ? 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('img/logo.png'))) : null,
        ])->render();

        $options = new Options;
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans'); // has the ₹ symbol
        $pdf = new Dompdf($options);
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->setPaper('A4');
        $pdf->render();

        return $pdf->output();
    }
}
