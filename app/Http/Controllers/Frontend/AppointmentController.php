<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // All services list
    private function services(): array
    {
        return [
            // Makeup
            ['name' => 'Professional Makeup Application', 'category' => 'Makeup',     'price' => 15000, 'duration' => 60],
            ['name' => 'Pre-Bridal Makeup Consulting',   'category' => 'Makeup',     'price' => 0,     'duration' => 30],
            ['name' => 'Private MakeUp Lesson',          'category' => 'Makeup',     'price' => 6000,  'duration' => 60],
            ['name' => 'Makeup Class',                   'category' => 'Makeup',     'price' => 0,     'duration' => 60],

            // Facials
            ['name' => 'Basic Facial',                   'category' => 'Facials',    'price' => 12500, 'duration' => 35],
            ['name' => 'Glownation Facial',              'category' => 'Facials',    'price' => 15000, 'duration' => 60],
            ['name' => 'Hydra Facial',                   'category' => 'Facials',    'price' => 17500, 'duration' => 45],
            ['name' => 'Nano Infusion Facial',           'category' => 'Facials',    'price' => 30000, 'duration' => 75],
            ['name' => 'Retinol Infusion Facial',        'category' => 'Facials',    'price' => 22000, 'duration' => 45],
            ['name' => 'Youth Restore Sculpting Facial', 'category' => 'Facials',    'price' => 28800, 'duration' => 75],
            ['name' => 'Hyperpigmentation Facial',       'category' => 'Facials',    'price' => 27500, 'duration' => 60],
            ['name' => 'Mother To Be Facial',            'category' => 'Facials',    'price' => 15200, 'duration' => 60],

            // Skin Treatments
            ['name' => 'PCA Sensi Chemical Peel',        'category' => 'Skin',       'price' => 12500, 'duration' => 45],
            ['name' => 'Dermaplaning',                   'category' => 'Skin',       'price' => 5000,  'duration' => 30],
            ['name' => 'Micro Needling',                 'category' => 'Skin',       'price' => 50000, 'duration' => 60],
            ['name' => 'Microneedling Eye Treatment',    'category' => 'Skin',       'price' => 5000,  'duration' => 30],

            // Body
            ['name' => 'Body Sculpting',                 'category' => 'Body',       'price' => 30000, 'duration' => 30],
            ['name' => 'Full Body Exfoliation',          'category' => 'Body',       'price' => 19500, 'duration' => 90],
            ['name' => 'Exfoliating Back Scrub',         'category' => 'Body',       'price' => 6500,  'duration' => 30],
            ['name' => 'Purifying Back Treatment',       'category' => 'Body',       'price' => 7000,  'duration' => 30],
            ['name' => 'Back Detox & Glow Treatment',   'category' => 'Body',       'price' => 16500, 'duration' => 60],
            ['name' => 'Purifying Mud Wrap',             'category' => 'Body',       'price' => 27500, 'duration' => 75],
            ['name' => 'Full Body Scrub + Sauna Blanket','category' => 'Body',       'price' => 24500, 'duration' => 90],

            // Massage
            ['name' => 'Lymphatic Body Massage',         'category' => 'Massage',    'price' => 12000, 'duration' => 60],
            ['name' => 'Back Massage',                   'category' => 'Massage',    'price' => 5000,  'duration' => 30],
            ['name' => 'Prenatal Massage',               'category' => 'Massage',    'price' => 6500,  'duration' => 30],
            ['name' => 'Aromatherapy Massage (60 min)',  'category' => 'Massage',    'price' => 13500, 'duration' => 60],

            // Hair
            ['name' => 'Protein Treatment',              'category' => 'Hair',       'price' => 5000,  'duration' => 30],
            ['name' => 'Texturizing / Retouching',       'category' => 'Hair',       'price' => 0,     'duration' => 90],
            ['name' => 'Oxygen Hair Root Therapy',       'category' => 'Hair',       'price' => 12000, 'duration' => 90],
            ['name' => 'Japanese Head Spa (45 min)',     'category' => 'Hair',       'price' => 14900, 'duration' => 45],
            ['name' => 'Box Braids',                     'category' => 'Hair',       'price' => 0,     'duration' => 300],

            // Lashes & Brows
            ['name' => 'Eyelash Extensions',             'category' => 'Lashes',     'price' => 13000, 'duration' => 120],
            ['name' => 'Lash Lift And Tint',             'category' => 'Lashes',     'price' => 13500, 'duration' => 90],
            ['name' => 'Eyebrows Tinting',               'category' => 'Lashes',     'price' => 3500,  'duration' => 30],
            ['name' => 'Eyebrows Lamination',            'category' => 'Lashes',     'price' => 7000,  'duration' => 30],

            // Wellness
            ['name' => 'VStream',                        'category' => 'Wellness',   'price' => 6000,  'duration' => 30],
            ['name' => 'Reiki',                          'category' => 'Wellness',   'price' => 9900,  'duration' => 45],
            ['name' => 'Colon Hydrotherapy',             'category' => 'Wellness',   'price' => 17500, 'duration' => 45],
            ['name' => 'Health Assessment',              'category' => 'Wellness',   'price' => 19900, 'duration' => 60],
            ['name' => 'Sauna Blanket (45 min)',         'category' => 'Wellness',   'price' => 9500,  'duration' => 45],

            // Waxing
            ['name' => 'Face Wax',                       'category' => 'Waxing',     'price' => 0,     'duration' => 30],
            ['name' => 'Body Waxing',                    'category' => 'Waxing',     'price' => 0,     'duration' => 30],

            // Consultation
            ['name' => 'Free Skin Consultation',         'category' => 'Consultation','price' => 0,    'duration' => 20],
            ['name' => 'Video Consult',                  'category' => 'Consultation','price' => 0,    'duration' => 30],
        ];
    }

    // Available time slots
    private function timeSlots(): array
    {
        return [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
        ];
    }

    // ── GET /book ────────────────────────────────────────────
    public function index()
    {
        $services   = $this->services();
        $categories = collect($services)->pluck('category')->unique()->values()->all();
        $timeSlots  = $this->timeSlots();

        return view('frontend.book.index', compact('services', 'categories', 'timeSlots'));
    }

    // ── POST /book ───────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name'      => 'required|string|max:100',
            'client_phone'     => 'required|string|max:20',
            'client_email'     => 'nullable|email|max:100',
            'service_name'     => 'required|string',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|string',
            'notes'            => 'nullable|string|max:500',
        ]);

        // Find service details
        $service = collect($this->services())
            ->firstWhere('name', $validated['service_name']);

        $appointment = Appointment::create([
            'client_name'      => $validated['client_name'],
            'client_phone'     => $validated['client_phone'],
            'client_email'     => $validated['client_email'] ?? null,
            'service_name'     => $validated['service_name'],
            'service_category' => $service['category'] ?? null,
            'service_price'    => $service['price'] ?? 0,
            'service_duration' => $service['duration'] ?? 30,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'notes'            => $validated['notes'] ?? null,
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
        ]);

        return redirect()->route('book.success', $appointment->id);
    }

    // ── GET /book/success/{id} ───────────────────────────────
    public function success(Appointment $appointment)
    {
        return view('frontend.book.success', compact('appointment'));
    }
}