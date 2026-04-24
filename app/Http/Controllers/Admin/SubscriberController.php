<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscriber::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $subscribers = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'    => Subscriber::count(),
            'active'   => Subscriber::active()->count(),
            'email'    => Subscriber::byType('email')->count(),
            'sms'      => Subscriber::byType('sms')->count(),
            'whatsapp' => Subscriber::byType('whatsapp')->count(),
            'push'     => Subscriber::byType('push')->count(),
        ];

        return view('admin.subscribers.index', compact('subscribers', 'stats'));
    }

    public function create()
    {
        return view('admin.subscribers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'nullable|string|max:255',
            'email'  => 'nullable|email|unique:subscribers,email',
            'phone'  => 'nullable|string|max:20',
            'type'   => 'required|in:email,sms,whatsapp,push',
            'source' => 'required|in:footer_form,checkout,manual,registration',
            'tag'    => 'nullable|string|max:100',
        ]);

        $validated['subscribed_at'] = now();
        $validated['is_active'] = true;

        Subscriber::create($validated);

        return redirect()->route('admin.subscribers.index')
            ->with('success', 'Subscriber added successfully.');
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->update(['is_active' => false, 'unsubscribed_at' => now()]);

        return back()->with('success', 'Subscriber unsubscribed successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Subscriber::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $subscribers = $query->get();

        return response()->streamDownload(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Type', 'Source', 'Tag', 'Status', 'Subscribed At']);

            foreach ($subscribers as $s) {
                fputcsv($handle, [
                    $s->id, $s->name, $s->email, $s->phone,
                    $s->type, $s->source, $s->tag,
                    $s->is_active ? 'Active' : 'Unsubscribed',
                    $s->subscribed_at?->format('Y-m-d H:i'),
                ]);
            }
            fclose($handle);
        }, 'subscribers_' . now()->format('Y_m_d') . '.csv');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type'    => 'required|in:email,sms,whatsapp,push,all',
        ]);

        // Hook into your notification/mail system here
        // Mail::to($emails)->send(new BroadcastMail(...));

        return back()->with('success', 'Message broadcast queued successfully.');
    }
}