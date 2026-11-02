<?php

namespace Tests\Feature;

use App\Filament\Resources\Articles\ArticleResource\Pages\CreateArticle;
use App\Filament\Resources\Articles\ArticleResource\Pages\EditArticle;
use App\Filament\Resources\Gallery\GalleryPhotoResource\Pages\EditGalleryPhoto;
use App\Filament\Resources\Katalog\KatalogResource\Pages\EditKatalog;
use App\Models\Article;
use App\Models\GalleryPhoto;
use App\Models\Katalog;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FilamentUploadSecurityTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('invalidUploads')]
    public function test_upload_fields_reject_unsafe_extensions_and_mime_types(string $page, string $field, string $filename, string $contentType): void
    {
        Storage::fake('public');
        config(['filament.default_filesystem_disk' => 'public']);
        $this->actingAs(User::factory()->create(['peran' => 'admin']));
        $record = $this->record($field);
        $originalPath = $record->getAttribute($field);
        $file = $this->upload($filename, $contentType);

        Livewire::test($page, ['record' => $record->getRouteKey()])
            ->fillForm([$field => $file])
            ->call('save')
            ->assertHasFormErrors([$field]);

        $this->assertSame($originalPath, $record->refresh()->getAttribute($field));
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    #[DataProvider('validUploads')]
    public function test_upload_fields_persist_random_names_with_detected_mime_extensions(string $page, string $field, string $contentType, string $extension): void
    {
        Storage::fake('public');
        config(['filament.default_filesystem_disk' => 'public']);
        $this->actingAs(User::factory()->create(['peran' => 'admin']));
        $record = $this->record($field);
        // An allowed but mismatched client extension must never determine the stored extension.
        $file = $this->upload('client-name.jpeg', $contentType);

        Livewire::test($page, ['record' => $record->getRouteKey()])
            ->fillForm([$field => $file])
            ->call('save')
            ->assertHasNoFormErrors();

        $path = $record->refresh()->getAttribute($field);
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{40}\.'.$extension.'$/', basename($path));
        Storage::disk('public')->assertExists($path);
        $this->assertSame($file->get(), Storage::disk('public')->get($path));
        $this->assertCount(1, Storage::disk('public')->allFiles());
    }

    #[DataProvider('invalidAttachments')]
    public function test_rich_editor_rejects_unsafe_extensions_and_mime_types(string $filename, string $contentType): void
    {
        Storage::fake('public');
        config(['filament.default_filesystem_disk' => 'public']);
        $this->actingAs(User::factory()->create(['peran' => 'admin']));

        Livewire::test(CreateArticle::class)
            ->set('componentFileAttachments.data.content', $this->upload($filename, $contentType))
            ->call('getFormComponentFileAttachmentUrl', 'data.content')
            ->assertHasErrors(['data.content']);

        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    #[DataProvider('validAttachments')]
    public function test_rich_editor_stores_attachments_with_detected_mime_extensions(string $contentType, string $extension): void
    {
        Storage::fake('public');
        config(['filament.default_filesystem_disk' => 'public']);
        $this->actingAs(User::factory()->create(['peran' => 'admin']));
        $file = $this->upload('client-name.jpeg', $contentType);

        $component = Livewire::test(CreateArticle::class)
            ->set('componentFileAttachments.data.content', $file)
            ->call('getFormComponentFileAttachmentUrl', 'data.content')
            ->assertHasNoErrors();

        $paths = Storage::disk('public')->allFiles();
        $this->assertCount(1, $paths);
        $path = $paths[0];
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{40}\.'.$extension.'$/', basename($path));
        $this->assertSame($file->get(), Storage::disk('public')->get($path));
        $component->assertReturned(Storage::disk('public')->url($path));
    }

    /** @return iterable<string, array{string, string, string, string}> */
    public static function invalidUploads(): iterable
    {
        foreach (self::uploadFields() as $field => $page) {
            foreach (self::invalidAttachments() as $name => [$filename, $contentType]) {
                yield $field.' '.$name => [$page, $field, $filename, $contentType];
            }
        }
    }

    /** @return iterable<string, array{string, string, string, string}> */
    public static function validUploads(): iterable
    {
        foreach (self::uploadFields() as $field => $page) {
            foreach (['jpg', 'png', 'webp'] as $extension) {
                yield $field.' '.$extension => [$page, $field, $extension, $extension];
            }
        }
    }

    /** @return iterable<string, array{string, string}> */
    public static function invalidAttachments(): iterable
    {
        foreach (['html', 'svg', 'php'] as $extension) {
            yield 'image disguised as '.$extension => ['payload.'.$extension, 'png'];
            yield $extension.' disguised as image' => ['payload.jpg', $extension];
        }
    }

    /** @return iterable<string, array{string, string}> */
    public static function validAttachments(): iterable
    {
        foreach (['jpg', 'png', 'webp', 'gif'] as $extension) {
            yield $extension => [$extension, $extension];
        }
    }

    /** @return array<string, class-string> */
    private static function uploadFields(): array
    {
        return [
            'header_image' => EditArticle::class,
            'image_path' => EditGalleryPhoto::class,
            'gambar' => EditKatalog::class,
        ];
    }

    private function upload(string $filename, string $contentType): UploadedFile
    {
        $content = match ($contentType) {
            'html' => '<html><script>alert(1)</script></html>',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>',
            'php' => '<?php echo "unsafe";',
            default => UploadedFile::fake()->image('image.'.$contentType)->get(),
        };

        return UploadedFile::fake()->createWithContent($filename, $content)
            ->mimeType((new \finfo(FILEINFO_MIME_TYPE))->buffer($content));
    }

    private function record(string $field): Article|GalleryPhoto|Katalog
    {
        return match ($field) {
            'header_image' => Article::create([
                'program_category_id' => ProgramCategory::create([
                    'name' => 'Upload security',
                    'slug' => 'upload-security',
                ])->id,
                'title' => 'Upload security',
                'slug' => 'upload-security',
                'content' => '<p>Safe content</p>',
            ]),
            'image_path' => GalleryPhoto::create([
                'title' => 'Upload security',
                'image_path' => 'gallery/existing.jpg',
                'category' => 'jumat_berkah',
                'activity_date' => '2026-11-02',
            ]),
            'gambar' => Katalog::create([
                'program_id' => Program::create([
                    'nama_program' => 'Upload security',
                    'slug' => 'upload-security',
                    'status_aktif' => true,
                ])->id,
                'judul' => 'Upload security',
                'slug' => 'upload-security',
                'deskripsi' => 'Safe content',
                'status' => 'aktif',
            ]),
        };
    }
}
