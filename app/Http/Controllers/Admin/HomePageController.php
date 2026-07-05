<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\HomePageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HomePageController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/HomePage/Edit', [
            'content' => HomePageContent::get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $content = $request->validate([
            'content' => ['required', 'array'],
            'content.hero' => ['required', 'array'],
            'content.hero.badge' => ['nullable', 'string', 'max:160'],
            'content.hero.title' => ['nullable', 'string', 'max:220'],
            'content.hero.highlight' => ['nullable', 'string', 'max:220'],
            'content.hero.description' => ['nullable', 'string', 'max:1200'],
            'content.hero.primary_button_text' => ['nullable', 'string', 'max:80'],
            'content.hero.primary_button_url' => ['nullable', 'string', 'max:255'],
            'content.hero.secondary_button_text' => ['nullable', 'string', 'max:80'],
            'content.hero.secondary_button_url' => ['nullable', 'string', 'max:255'],
            'content.hero.accreditation_label' => ['nullable', 'string', 'max:120'],
            'content.hero.egac_image' => ['nullable', 'string', 'max:255'],
            'content.hero.egac_alt' => ['nullable', 'string', 'max:255'],
            'content.hero.iso_image' => ['nullable', 'string', 'max:255'],
            'content.hero.iso_alt' => ['nullable', 'string', 'max:255'],
            'content.stats' => ['required', 'array', 'max:8'],
            'content.stats.*.value' => ['nullable', 'string', 'max:80'],
            'content.stats.*.label' => ['nullable', 'string', 'max:220'],
            'content.welcome' => ['required', 'array'],
            'content.welcome.eyebrow' => ['nullable', 'string', 'max:120'],
            'content.welcome.title' => ['nullable', 'string', 'max:220'],
            'content.welcome.paragraphs' => ['required', 'array', 'max:8'],
            'content.welcome.paragraphs.*' => ['nullable', 'string', 'max:1500'],
            'content.testing' => ['required', 'array'],
            'content.testing.title' => ['nullable', 'string', 'max:160'],
            'content.testing.subtitle' => ['nullable', 'string', 'max:220'],
            'content.testing.items' => ['required', 'array', 'max:10'],
            'content.testing.items.*' => ['nullable', 'string', 'max:220'],
            'content.vision_mission' => ['required', 'array'],
            'content.vision_mission.eyebrow' => ['nullable', 'string', 'max:120'],
            'content.vision_mission.title' => ['nullable', 'string', 'max:220'],
            'content.vision_mission.description' => ['nullable', 'string', 'max:1200'],
            'content.vision_mission.image' => ['nullable', 'string', 'max:255'],
            'content.vision_mission.image_alt' => ['nullable', 'string', 'max:255'],
            'content.vision_mission.mission_title' => ['nullable', 'string', 'max:120'],
            'content.vision_mission.vision_title' => ['nullable', 'string', 'max:120'],
            'content.vision_mission.mission' => ['required', 'array', 'max:10'],
            'content.vision_mission.mission.*' => ['nullable', 'string', 'max:300'],
            'content.vision_mission.vision' => ['required', 'array', 'max:10'],
            'content.vision_mission.vision.*' => ['nullable', 'string', 'max:300'],
            'content.services' => ['required', 'array'],
            'content.services.eyebrow' => ['nullable', 'string', 'max:120'],
            'content.services.title' => ['nullable', 'string', 'max:220'],
            'content.services.items' => ['required', 'array', 'max:6'],
            'content.services.items.*.title' => ['nullable', 'string', 'max:160'],
            'content.services.items.*.desc' => ['nullable', 'string', 'max:1200'],
            'content.services.items.*.img' => ['nullable', 'string', 'max:255'],
            'content.services.items.*.link_text' => ['nullable', 'string', 'max:80'],
            'content.services.items.*.link_url' => ['nullable', 'string', 'max:255'],
            'content.cta' => ['required', 'array'],
            'content.cta.title' => ['nullable', 'string', 'max:220'],
            'content.cta.description' => ['nullable', 'string', 'max:600'],
            'content.cta.primary_button_text' => ['nullable', 'string', 'max:80'],
            'content.cta.primary_button_url' => ['nullable', 'string', 'max:255'],
            'content.cta.secondary_button_text' => ['nullable', 'string', 'max:80'],
            'content.cta.secondary_button_url' => ['nullable', 'string', 'max:255'],
            'hero_egac_image' => ['nullable', 'image', 'max:4096'],
            'hero_iso_image' => ['nullable', 'image', 'max:4096'],
            'vision_image' => ['nullable', 'image', 'max:4096'],
            'service_0_image' => ['nullable', 'image', 'max:4096'],
            'service_1_image' => ['nullable', 'image', 'max:4096'],
            'service_2_image' => ['nullable', 'image', 'max:4096'],
            'service_3_image' => ['nullable', 'image', 'max:4096'],
            'service_4_image' => ['nullable', 'image', 'max:4096'],
            'service_5_image' => ['nullable', 'image', 'max:4096'],
        ])['content'];

        $this->applyImageUpload($request, $content, 'hero_egac_image', ['hero', 'egac_image']);
        $this->applyImageUpload($request, $content, 'hero_iso_image', ['hero', 'iso_image']);
        $this->applyImageUpload($request, $content, 'vision_image', ['vision_mission', 'image']);

        foreach ([0, 1, 2, 3, 4, 5] as $index) {
            $this->applyImageUpload($request, $content, "service_{$index}_image", ['services', 'items', $index, 'img']);
        }

        HomePageContent::save($content);

        return redirect()->route('admin.homepage.edit')
            ->with('success', 'Homepage content updated successfully.');
    }

    /**
     * @param  array<string, mixed>  $content
     * @param  array<int, string|int>  $path
     */
    private function applyImageUpload(Request $request, array &$content, string $input, array $path): void
    {
        if (! $request->hasFile($input)) {
            return;
        }

        $storedPath = Storage::url($request->file($input)->store('homepage', 'public'));
        $target = &$content;
        $lastIndex = array_key_last($path);

        foreach ($path as $index => $segment) {
            if ($index === $lastIndex) {
                $target[$segment] = $storedPath;

                return;
            }

            if (! isset($target[$segment]) || ! is_array($target[$segment])) {
                $target[$segment] = [];
            }

            $target = &$target[$segment];
        }
    }
}
