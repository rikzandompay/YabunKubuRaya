<?php

namespace Tests\Feature;

use App\Models\Donatur;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CsvExportSecurityTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('exportValues')]
    public function test_csv_export_neutralizes_formulas_without_changing_normal_text_or_amounts(string $value, string $expected): void
    {
        $donor = Donatur::create(['nama_donatur' => $value, 'tipe_donatur' => 'individu']);
        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 750000,
            'donor_id' => $donor->id,
            'description' => $value,
            'transaction_date' => '2026-11-02',
        ]);

        $response = $this->actingAs(User::factory()->create(['peran' => 'admin']))
            ->get(route('admin.dashboard.export'));

        $response->assertOk()->assertDownload();
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $response->streamedContent());
        rewind($stream);
        $header = fgetcsv($stream, separator: ';', escape: '');
        $row = fgetcsv($stream, separator: ';', escape: '');
        $nextRow = fgetcsv($stream, separator: ';', escape: '');
        fclose($stream);

        $this->assertCount(10, $header);
        $this->assertCount(10, $row);
        $this->assertSame($expected, $row[3]);
        $this->assertSame($expected, $row[9]);
        $this->assertSame('750000', $row[7]);
        $this->assertFalse($nextRow);
    }

    /** @return array<string, array{string, string}> */
    public static function exportValues(): array
    {
        return [
            'equals' => ['=1+1', "'=1+1"],
            'plus' => ['+1+1', "'+1+1"],
            'minus' => ['-1+1', "'-1+1"],
            'at' => ['@SUM(1)', "'@SUM(1)"],
            'spaces before formula' => ['  =1+1', "'  =1+1"],
            'tab before formula' => ["\t=1+1", "'\t=1+1"],
            'newline' => ["\nNormal text", "'\nNormal text"],
            'carriage return' => ["\rNormal text", "'\rNormal text"],
            'control before formula' => ["\x01=1+1", "'\x01=1+1"],
            'normal name' => ['Donatur Aman', 'Donatur Aman'],
            'embedded punctuation' => ['Budi + Keluarga', 'Budi + Keluarga'],
            'CSV delimiters and quotes' => ['Budi; "Keluarga"', 'Budi; "Keluarga"'],
        ];
    }
}
