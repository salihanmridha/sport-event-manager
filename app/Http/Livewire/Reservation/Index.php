<?php

namespace App\Http\Livewire\Reservation;

use App\Enums\EventStatusEnum;
use App\Enums\VenueBookingStatus;
use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\VenueBooking;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public string $response;

    public array $filters = [];

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
        'filters' => [
            'response' => 0,
            'date_range' => null,
        ]
    ];

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new VenueBooking())->orderable;
    }

    public function render()
    {
        $response = $this->filters['response'] ?? null;

        $query = VenueBooking::query()
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ])
            ->with(['creator:id,email', 'venue:id,name'])
            ->with(['event' => function ($q) {
                return $q->with('user_create:id,first_name,last_name,email');
            }])
            ->whereHas('venue', function ($q) {
                $q->where('owner_id', auth()->id());
            })
            ->where(function ($q) {
                $q->whereHas('event', function ($q) {
                    $q->where('status', EventStatusEnum::on_going());
                })->orWhere('response', VenueBookingStatus::booked());
            })
            ->when(isset($response) && $response != '',
                function ($q) use ($response) {
                    $q->where('response', $response);
                });

        if (isset($this->filters['date_range'])) {
            [$startDate, $endDate] = $this->getDateRange($this->filters['date_range']);
            $query->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('venue_booking.created_at', '>=', $startDate);
            })->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('venue_booking.created_at', '<=', $endDate);
            });
        }

        $reservations = $query->paginate($this->perPage);

        return view('livewire.reservation.index', compact('reservations', 'query'));
    }

    public function getDateRange($range): array
    {
        $arrayDate = explode(' to ', $range, 2);

        // Check: Date is valid
        $checkStartDate = date_parse($arrayDate[0] ?? null);
        $checkEndDate = date_parse($arrayDate[1] ?? null);
        $startDate = $checkStartDate['error_count'] ? null : $arrayDate[0];
        $endDate = $checkEndDate['error_count'] ? null : $arrayDate[1];

        return [$startDate, $endDate];
    }

}
