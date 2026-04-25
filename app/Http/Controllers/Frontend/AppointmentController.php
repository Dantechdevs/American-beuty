<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // ── All spa services ────────────────────────────────────
    private function getServices(): array
    {
        return [

            // ── HYDRAFACIALS ─────────────────────────────────
            [
                'category'      => 'HydraFacials',
                'name'          => 'Express HydraFacial',
                'description'   => 'Deep cleansing, exfoliation, extraction, hydration + serum infusion for instant glow.',
                'price'         => 6000,
                'price_label'   => 'Ksh 6,000 – 8,000',
                'duration'      => 35,
                'duration_label'=> '30–40 mins',
            ],
            [
                'category'      => 'HydraFacials',
                'name'          => 'Deluxe HydraFacial',
                'description'   => 'Enhanced HydraFacial combining multiple steps: cleansing, extraction and deep hydration for a visible glow.',
                'price'         => 8000,
                'price_label'   => 'Ksh 8,000 – 12,000',
                'duration'      => 52,
                'duration_label'=> '45–60 mins',
            ],
            [
                'category'      => 'HydraFacials',
                'name'          => 'Advanced HydraFacial (LED / Boosters)',
                'description'   => 'Premium HydraFacial with LED therapy and targeted boosters for maximum skin transformation.',
                'price'         => 12000,
                'price_label'   => 'Ksh 12,000 – 25,000',
                'duration'      => 67,
                'duration_label'=> '60–75 mins',
            ],

            // ── QUICK / EXPRESS FACIALS ───────────────────────
            [
                'category'      => 'Quick / Express Facials',
                'name'          => 'Mini Facial',
                'description'   => 'Basic cleansing, exfoliation, mask and moisturising — ideal for quick maintenance.',
                'price'         => 3000,
                'price_label'   => 'Ksh 3,000',
                'duration'      => 25,
                'duration_label'=> '20–30 mins',
            ],
            [
                'category'      => 'Quick / Express Facials',
                'name'          => 'Express Facial',
                'description'   => 'A thorough yet speedy facial covering cleansing, exfoliation, mask and hydration.',
                'price'         => 3000,
                'price_label'   => 'Ksh 3,000 – 4,500',
                'duration'      => 37,
                'duration_label'=> '30–45 mins',
            ],

            // ── ANTI-AGING FACIALS ────────────────────────────
            [
                'category'      => 'Anti-Aging Facials',
                'name'          => 'Classic Anti-Aging Facial',
                'description'   => 'Targets wrinkles, fine lines and dullness using collagen-boosting products for a youthful look.',
                'price'         => 7000,
                'price_label'   => 'Ksh 7,000 – 10,000',
                'duration'      => 60,
                'duration_label'=> '60 mins',
            ],
            [
                'category'      => 'Anti-Aging Facials',
                'name'          => 'Advanced Anti-Aging (Retinol / LED / Oxygen)',
                'description'   => 'Combines retinol, LED light therapy and oxygen infusion to deeply combat signs of aging.',
                'price'         => 10000,
                'price_label'   => 'Ksh 10,000 – 15,000',
                'duration'      => 67,
                'duration_label'=> '60–75 mins',
            ],
            [
                'category'      => 'Anti-Aging Facials',
                'name'          => 'Premium Anti-Aging (Microneedling / RF Combo)',
                'description'   => 'Our most powerful anti-aging treatment combining microneedling and radiofrequency for dramatic skin renewal.',
                'price'         => 15000,
                'price_label'   => 'Ksh 15,000 – 40,000',
                'duration'      => 82,
                'duration_label'=> '75–90 mins',
            ],

            // ── OTHER / CUSTOMIZED FACIALS ────────────────────
            [
                'category'      => 'Customized Facials',
                'name'          => 'Acne / Detox Facial',
                'description'   => 'Targets breakouts and congested skin with deep cleansing, extraction and calming treatments.',
                'price'         => 4000,
                'price_label'   => 'Ksh 4,000 – 8,000',
                'duration'      => 52,
                'duration_label'=> '45–60 mins',
            ],
            [
                'category'      => 'Customized Facials',
                'name'          => 'Brightening / Glow Facial',
                'description'   => 'Revives dull skin with brightening actives and a radiance-boosting treatment for an instant glow.',
                'price'         => 6000,
                'price_label'   => 'Ksh 6,000 – 10,000',
                'duration'      => 60,
                'duration_label'=> '60 mins',
            ],
            [
                'category'      => 'Customized Facials',
                'name'          => 'Hydrating Facial',
                'description'   => 'Deeply replenishes moisture levels using hyaluronic and nourishing serums for plump, dewy skin.',
                'price'         => 6500,
                'price_label'   => 'Ksh 6,500 – 8,500',
                'duration'      => 65,
                'duration_label'=> '60–70 mins',
            ],
            [
                'category'      => 'Customized Facials',
                'name'          => 'Deep Cleansing Facial',
                'description'   => 'Includes extraction, purifying mask and hydration for balanced, clear skin.',
                'price'         => 5000,
                'price_label'   => 'Ksh 5,000 – 7,000',
                'duration'      => 60,
                'duration_label'=> '60 mins',
            ],

            // ── MICROBLADING ──────────────────────────────────
            [
                'category'      => 'Microblading',
                'name'          => 'Microblading – Initial Session',
                'description'   => 'Semi-permanent eyebrow tattoo for fuller, natural-looking brows that last 1–2 years.',
                'price'         => 10000,
                'price_label'   => 'Ksh 10,000 – 15,000',
                'duration'      => 150,
                'duration_label'=> '2–3 hrs',
            ],
            [
                'category'      => 'Microblading',
                'name'          => 'Microblading Touch-Up (4–6 weeks)',
                'description'   => 'Follow-up session to perfect shape, fill gaps and lock in colour after initial healing.',
                'price'         => 5000,
                'price_label'   => 'Ksh 5,000 – 10,000',
                'duration'      => 90,
                'duration_label'=> '1–2 hrs',
            ],
            [
                'category'      => 'Microblading',
                'name'          => 'Annual Colour Boost',
                'description'   => 'Yearly refresh to maintain the vibrancy and definition of your microbladed brows.',
                'price'         => 8000,
                'price_label'   => 'Ksh 8,000 – 15,000',
                'duration'      => 90,
                'duration_label'=> '1–2 hrs',
            ],

            // ── WAXING ────────────────────────────────────────
            [
                'category'      => 'Waxing',
                'name'          => 'Eyebrow Wax',
                'description'   => 'Precise eyebrow shaping using warm wax for clean, defined brows.',
                'price'         => 300,
                'price_label'   => 'Ksh 300 – 500',
                'duration'      => 12,
                'duration_label'=> '10–15 mins',
            ],
            [
                'category'      => 'Waxing',
                'name'          => 'Underarm Wax',
                'description'   => 'Quick and effective underarm hair removal with warm wax.',
                'price'         => 500,
                'price_label'   => 'Ksh 500 – 1,000',
                'duration'      => 15,
                'duration_label'=> '15 mins',
            ],
            [
                'category'      => 'Waxing',
                'name'          => 'Half Leg Wax',
                'description'   => 'Smooth hair removal from the knee down using warm wax.',
                'price'         => 1000,
                'price_label'   => 'Ksh 1,000 – 2,000',
                'duration'      => 25,
                'duration_label'=> '20–30 mins',
            ],
            [
                'category'      => 'Waxing',
                'name'          => 'Full Leg Wax',
                'description'   => 'Complete leg hair removal from ankle to upper thigh.',
                'price'         => 2000,
                'price_label'   => 'Ksh 2,000 – 3,000',
                'duration'      => 37,
                'duration_label'=> '30–45 mins',
            ],
            [
                'category'      => 'Waxing',
                'name'          => 'Brazilian / Bikini Wax',
                'description'   => 'Full bikini area hair removal — price depends on area and coverage.',
                'price'         => 3000,
                'price_label'   => 'Ksh 3,000 – 5,000',
                'duration'      => 30,
                'duration_label'=> '30 mins',
            ],

            // ── SKIN TAG & MOLE REMOVAL ───────────────────────
            [
                'category'      => 'Skin Tag & Mole Removal',
                'name'          => 'Skin Tag Removal (per tag)',
                'description'   => 'Safe removal of individual skin tags using cautery or minor procedure.',
                'price'         => 1000,
                'price_label'   => 'Ksh 1,000 – 3,000',
                'duration'      => 15,
                'duration_label'=> '10–20 mins',
            ],
            [
                'category'      => 'Skin Tag & Mole Removal',
                'name'          => 'Multiple Skin Tags Package',
                'description'   => 'Discounted package for removal of multiple skin tags in a single session.',
                'price'         => 5000,
                'price_label'   => 'Ksh 5,000 – 15,000',
                'duration'      => 45,
                'duration_label'=> '30–60 mins',
            ],
            [
                'category'      => 'Skin Tag & Mole Removal',
                'name'          => 'Mole Removal (small)',
                'description'   => 'Safe removal of small moles using clinically approved procedures.',
                'price'         => 3000,
                'price_label'   => 'Ksh 3,000 – 10,000',
                'duration'      => 30,
                'duration_label'=> '20–40 mins',
            ],
            [
                'category'      => 'Skin Tag & Mole Removal',
                'name'          => 'Advanced Mole Removal (Medical / Laser)',
                'description'   => 'Medical-grade or laser mole removal for larger or complex moles.',
                'price'         => 10000,
                'price_label'   => 'Ksh 10,000 – 25,000',
                'duration'      => 45,
                'duration_label'=> '30–60 mins',
            ],

            // ── ADD-ONS ───────────────────────────────────────
            [
                'category'      => 'Add-Ons',
                'name'          => 'LED Therapy',
                'description'   => 'Light therapy add-on to boost collagen, reduce inflammation and enhance any facial treatment.',
                'price'         => 2000,
                'price_label'   => 'Ksh 2,000 – 5,000',
                'duration'      => 25,
                'duration_label'=> '20–30 mins',
            ],
            [
                'category'      => 'Add-Ons',
                'name'          => 'Face Massage',
                'description'   => 'Relaxing facial massage to boost circulation and lymphatic drainage.',
                'price'         => 1500,
                'price_label'   => 'Ksh 1,500 – 3,000',
                'duration'      => 17,
                'duration_label'=> '15–20 mins',
            ],
            [
                'category'      => 'Add-Ons',
                'name'          => 'Chemical Peel',
                'description'   => 'Exfoliating peel to resurface skin, reduce pigmentation and improve texture.',
                'price'         => 5000,
                'price_label'   => 'Ksh 5,000 – 15,000',
                'duration'      => 30,
                'duration_label'=> '30 mins',
            ],
            [
                'category'      => 'Add-Ons',
                'name'          => 'Neck & Décolleté Treatment',
                'description'   => 'Targeted treatment for the neck and chest area to firm, brighten and hydrate.',
                'price'         => 2000,
                'price_label'   => 'Ksh 2,000 – 4,000',
                'duration'      => 20,
                'duration_label'=> '20 mins',
            ],

        ];
    }

    // ── GET /book ────────────────────────────────────────────
    public function index()
    {
        $services = $this->getServices();

        $categories = collect($services)
            ->pluck('category')
            ->unique()
            ->values()
            ->all();

        $timeSlots = [
            '8:00 AM',  '8:30 AM',  '9:00 AM',  '9:30 AM',
            '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
            '12:00 PM', '12:30 PM', '1:00 PM',  '1:30 PM',
            '2:00 PM',  '2:30 PM',  '3:00 PM',  '3:30 PM',
            '4:00 PM',  '4:30 PM',  '5:00 PM',  '5:30 PM',
        ];

        return view('frontend.book.index', compact('services', 'categories', 'timeSlots'));
    }

    // ── POST /book ───────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'service_name'     => 'required|string',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|string',
            'client_name'      => 'required|string|max:120',
            'client_phone'     => 'required|string|max:20',
            'client_email'     => 'nullable|email|max:120',
            'notes'            => 'nullable|string|max:1000',
        ]);

        // Find the selected service to pull price, duration, category
        $services       = $this->getServices();
        $selectedService= collect($services)->firstWhere('name', $request->service_name);

        $appointment = Appointment::create([
            'client_name'      => $request->client_name,
            'client_phone'     => $request->client_phone,
            'client_email'     => $request->client_email,
            'service_name'     => $request->service_name,
            'service_category' => $selectedService['category']   ?? null,
            'service_price'    => $selectedService['price']      ?? 0,
            'service_duration' => $selectedService['duration']   ?? 60,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes'            => $request->notes,
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
        ]);

        return redirect()->route('book.success', $appointment);
    }

    // ── GET /book/success/{appointment} ─────────────────────
    public function success(Appointment $appointment)
    {
        return view('frontend.book.success', compact('appointment'));
    }
}