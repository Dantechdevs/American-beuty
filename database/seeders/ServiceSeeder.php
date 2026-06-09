<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'HydraFacials', 'icon' => '💧', 'color_class' => 'c-hydra', 'services' => [
                ['name' => 'Express HydraFacial', 'desc' => 'Deep cleansing, exfoliation, extraction, hydration + serum infusion for an instant luminous glow.', 'price' => 'Ksh 6,000 – 8,000', 'duration' => '30–40 mins'],
                ['name' => 'Deluxe HydraFacial', 'desc' => 'Enhanced multi-step HydraFacial combining deep cleansing, extraction and intense hydration for a visible transformation.', 'price' => 'Ksh 8,000 – 12,000', 'duration' => '45–60 mins'],
                ['name' => 'Advanced HydraFacial (LED / Boosters)', 'desc' => 'Premium HydraFacial with LED therapy and targeted boosters for maximum skin renewal and radiance.', 'price' => 'Ksh 12,000 – 25,000', 'duration' => '60–75 mins'],
            ]],
            ['name' => 'Quick / Express Facials', 'icon' => '⚡', 'color_class' => 'c-express', 'services' => [
                ['name' => 'Mini Facial', 'desc' => 'Basic cleansing, exfoliation, mask and moisturising — ideal for a quick skin maintenance refresh.', 'price' => 'Ksh 3,000', 'duration' => '20–30 mins'],
                ['name' => 'Express Facial', 'desc' => 'A thorough yet swift facial covering cleansing, exfoliation, mask and deep hydration.', 'price' => 'Ksh 3,000 – 4,500', 'duration' => '30–45 mins'],
            ]],
            ['name' => 'Anti-Aging Facials', 'icon' => '✨', 'color_class' => 'c-antiage', 'services' => [
                ['name' => 'Classic Anti-Aging Facial', 'desc' => 'Targets wrinkles, fine lines and dullness using collagen-boosting actives for a youthful, firmer look.', 'price' => 'Ksh 7,000 – 10,000', 'duration' => '60 mins'],
                ['name' => 'Advanced Anti-Aging (Retinol / LED / Oxygen)', 'desc' => 'Combines retinol, LED light therapy and oxygen infusion to deeply combat visible signs of aging.', 'price' => 'Ksh 10,000 – 15,000', 'duration' => '60–75 mins'],
                ['name' => 'Premium Anti-Aging (Microneedling / RF Combo)', 'desc' => 'Our most powerful treatment combining microneedling and radiofrequency for dramatic skin renewal.', 'price' => 'Ksh 15,000 – 40,000', 'duration' => '75–90 mins'],
            ]],
            ['name' => 'Customized Facials', 'icon' => '🌿', 'color_class' => 'c-custom', 'services' => [
                ['name' => 'Acne / Detox Facial', 'desc' => 'Targets breakouts and congested skin with deep cleansing, careful extraction and calming actives.', 'price' => 'Ksh 4,000 – 8,000', 'duration' => '45–60 mins'],
                ['name' => 'Brightening / Glow Facial', 'desc' => 'Revives dull skin with brightening actives and a radiance-boosting protocol for an instant glow.', 'price' => 'Ksh 6,000 – 10,000', 'duration' => '60 mins'],
                ['name' => 'Hydrating Facial', 'desc' => 'Deeply replenishes moisture using hyaluronic and nourishing serums for plump, dewy skin.', 'price' => 'Ksh 6,500 – 8,500', 'duration' => '60–70 mins'],
                ['name' => 'Deep Cleansing Facial', 'desc' => 'Extraction, purifying mask and hydration for balanced, clean and healthy-looking skin.', 'price' => 'Ksh 5,000 – 7,000', 'duration' => '60 mins'],
            ]],
            ['name' => 'Microblading', 'icon' => '🖊️', 'color_class' => 'c-micro', 'services' => [
                ['name' => 'Microblading – Initial Session', 'desc' => 'Semi-permanent eyebrow tattoo for fuller, natural-looking brows that last 1–2 years.', 'price' => 'Ksh 10,000 – 15,000', 'duration' => '2–3 hrs'],
                ['name' => 'Microblading Touch-Up (4–6 weeks)', 'desc' => 'Follow-up session to perfect shape, fill gaps and lock in colour after initial healing.', 'price' => 'Ksh 5,000 – 10,000', 'duration' => '1–2 hrs'],
                ['name' => 'Annual Colour Boost', 'desc' => 'Yearly refresh to maintain the vibrancy and crisp definition of your microbladed brows.', 'price' => 'Ksh 8,000 – 15,000', 'duration' => '1–2 hrs'],
            ]],
            ['name' => 'Waxing', 'icon' => '🌸', 'color_class' => 'c-wax', 'services' => [
                ['name' => 'Eyebrow Wax', 'desc' => 'Precise eyebrow shaping using warm wax for clean, perfectly defined brows.', 'price' => 'Ksh 300 – 500', 'duration' => '10–15 mins'],
                ['name' => 'Underarm Wax', 'desc' => 'Quick and effective underarm hair removal with warm wax for smooth, long-lasting results.', 'price' => 'Ksh 500 – 1,000', 'duration' => '15 mins'],
                ['name' => 'Half Leg Wax', 'desc' => 'Smooth hair removal from the knee down using gentle warm wax.', 'price' => 'Ksh 1,000 – 2,000', 'duration' => '20–30 mins'],
                ['name' => 'Full Leg Wax', 'desc' => 'Complete leg hair removal from ankle to upper thigh for silky smooth skin.', 'price' => 'Ksh 2,000 – 3,000', 'duration' => '30–45 mins'],
                ['name' => 'Brazilian / Bikini Wax', 'desc' => 'Full bikini area hair removal — coverage options available, price varies by area.', 'price' => 'Ksh 3,000 – 5,000', 'duration' => '30 mins'],
            ]],
            ['name' => 'Skin Tag & Mole Removal', 'icon' => '🩺', 'color_class' => 'c-skin', 'services' => [
                ['name' => 'Skin Tag Removal (per tag)', 'desc' => 'Safe and precise removal of individual skin tags using cautery or a minor clinical procedure.', 'price' => 'Ksh 1,000 – 3,000', 'duration' => '10–20 mins'],
                ['name' => 'Multiple Skin Tags Package', 'desc' => 'Discounted package for the removal of multiple skin tags in a single convenient session.', 'price' => 'Ksh 5,000 – 15,000', 'duration' => '30–60 mins'],
                ['name' => 'Mole Removal (small)', 'desc' => 'Safe removal of small moles using clinically approved procedures with minimal downtime.', 'price' => 'Ksh 3,000 – 10,000', 'duration' => '20–40 mins'],
                ['name' => 'Advanced Mole Removal (Medical / Laser)', 'desc' => 'Medical-grade or laser mole removal for larger or more complex cases.', 'price' => 'Ksh 10,000 – 25,000', 'duration' => '30–60 mins'],
            ]],
            ['name' => 'Add-Ons', 'icon' => '➕', 'color_class' => 'c-addon', 'services' => [
                ['name' => 'LED Therapy', 'desc' => 'Light therapy add-on to boost collagen, reduce inflammation and elevate any facial treatment.', 'price' => 'Ksh 2,000 – 5,000', 'duration' => '20–30 mins'],
                ['name' => 'Face Massage', 'desc' => 'Relaxing facial massage to boost circulation, improve lymphatic drainage and ease tension.', 'price' => 'Ksh 1,500 – 3,000', 'duration' => '15–20 mins'],
                ['name' => 'Chemical Peel', 'desc' => 'Exfoliating peel to resurface skin, visibly reduce pigmentation and refine skin texture.', 'price' => 'Ksh 5,000 – 15,000', 'duration' => '30 mins'],
                ['name' => 'Neck & Décolleté Treatment', 'desc' => 'Targeted firming, brightening and hydrating treatment for the neck and chest area.', 'price' => 'Ksh 2,000 – 4,000', 'duration' => '20 mins'],
            ]],
        ];

        foreach ($data as $i => $catData) {
            $cat = ServiceCategory::firstOrCreate(
                ['slug' => Str::slug($catData['name'])],
                [
                    'name'        => $catData['name'],
                    'icon'        => $catData['icon'],
                    'color_class' => $catData['color_class'],
                    'sort_order'  => $i,
                    'is_active'   => true,
                ]
            );

            foreach ($catData['services'] as $j => $svc) {
                Service::firstOrCreate(
                    ['service_category_id' => $cat->id, 'name' => $svc['name']],
                    [
                        'description'   => $svc['desc'],
                        'price_display' => $svc['price'],
                        'duration'      => $svc['duration'],
                        'sort_order'    => $j,
                        'is_active'     => true,
                    ]
                );
            }
        }
    }
}
