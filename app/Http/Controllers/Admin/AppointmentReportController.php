<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AppointmentReportController extends Controller
{
    public function index(Request $request)
    {
        $period   = $request->get('period', 'monthly');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        [$from, $to] = $this->resolveDates($period, $dateFrom, $dateTo);

        $inRange   = Appointment::whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()]);
        $paidRange = Appointment::whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()])
                                ->where('payment_status', 'paid');

        $stats = [
            'total_appointments' => (clone $inRange)->count(),
            'total_revenue'      => (clone $paidRange)->sum('service_price'),
            'avg_value'          => (clone $paidRange)->avg('service_price') ?? 0,
            'confirmed'          => (clone $inRange)->where('status', 'confirmed')->count(),
            'completed'          => (clone $inRange)->where('status', 'completed')->count(),
            'cancelled'          => (clone $inRange)->where('status', 'cancelled')->count(),
            'pending'            => (clone $inRange)->where('status', 'pending')->count(),
            'paid_count'         => (clone $paidRange)->count(),
        ];

        $chartData = $this->getChartData($period, $from, $to);

        $topServices = (clone $paidRange)
            ->select('service_name', DB::raw('count(*) as bookings'), DB::raw('sum(service_price) as revenue'))
            ->groupBy('service_name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        $topCategories = (clone $paidRange)
            ->select('service_category', DB::raw('count(*) as bookings'), DB::raw('sum(service_price) as revenue'))
            ->groupBy('service_category')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get();

        $statusBreakdown = (clone $inRange)
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(service_price) as total'))
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();

        $paymentBreakdown = (clone $paidRange)
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(service_price) as revenue'))
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get();

        $recent = (clone $inRange)
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->limit(15)
            ->get();

        return view('admin.reports.appointments', compact(
            'stats', 'chartData', 'topServices', 'topCategories',
            'statusBreakdown', 'paymentBreakdown', 'recent',
            'period', 'from', 'to'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $period = $request->get('period', 'monthly');
        [$from, $to] = $this->resolveDates($period, $request->date_from, $request->date_to);

        $appointments = Appointment::whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()])
            ->orderByDesc('appointment_date')->get();

        return response()->streamDownload(function () use ($appointments) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Date','Time','Client','Phone','Service','Category','Price','Payment Status','Payment Method','Status','M-PESA Code']);
            foreach ($appointments as $a) {
                fputcsv($h, [
                    $a->appointment_date->format('Y-m-d'),
                    $a->appointment_time,
                    $a->client_name,
                    $a->client_phone,
                    $a->service_name,
                    $a->service_category,
                    $a->service_price,
                    $a->payment_status,
                    $a->payment_method,
                    $a->status,
                    $a->mpesa_code,
                ]);
            }
            fclose($h);
        }, 'appointments-' . $from->format('Y-m-d') . '-to-' . $to->format('Y-m-d') . '.csv');
    }

    private function resolveDates(string $period, ?string $from, ?string $to): array
    {
        if ($period === 'custom' && $from && $to) {
            return [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()];
        }
        return match($period) {
            'today'   => [now()->startOfDay(), now()->endOfDay()],
            'weekly'  => [now()->startOfWeek(), now()->endOfWeek()],
            'yearly'  => [now()->startOfYear(), now()->endOfYear()],
            default   => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    private function getChartData(string $period, Carbon $from, Carbon $to): array
    {
        $format = $period === 'yearly' ? '%Y-%m' : '%Y-%m-%d';
        $rows = Appointment::whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()])
            ->where('payment_status', 'paid')
            ->select(
                DB::raw("DATE_FORMAT(appointment_date, '{$format}') as label"),
                DB::raw('sum(service_price) as revenue'),
                DB::raw('count(*) as count')
            )
            ->groupBy('label')->orderBy('label')->get();

        return [
            'labels'  => $rows->pluck('label')->toArray(),
            'revenue' => $rows->pluck('revenue')->toArray(),
            'count'   => $rows->pluck('count')->toArray(),
        ];
    }
}
