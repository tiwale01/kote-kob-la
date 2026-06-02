<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Support\DonationMetrics;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonationExportController extends Controller
{
    public function __invoke(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $tempFolder = storage_path('app/export-temp');

            if (! is_dir($tempFolder)) {
                mkdir($tempFolder, 0775, true);
            }

            $options = new Options();
            $options->setTempFolder($tempFolder);

            $writer = new Writer($options);
            $writer->openToFile('php://output');

            $writer->addRow(Row::fromValues([
                'Collection',
                'Donor Name',
                'Lakou',
                'Lokalite',
                'Amount',
                'Paid',
                'Notes',
            ]));

            DonationMetrics::donationQuery()
                ->with('collection')
                ->orderBy('donor_name')
                ->each(function (Donation $donation) use ($writer): void {
                    $writer->addRow(Row::fromValues([
                        $donation->collection?->name,
                        $donation->donor_name,
                        $donation->lakou,
                        $donation->lokalite,
                        (float) $donation->amount,
                        $donation->is_paid ? 'Yes' : 'No',
                        $donation->notes,
                    ]));
                });

            $writer->close();
        }, 'donations.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
