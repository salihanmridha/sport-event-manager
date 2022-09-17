<?php

namespace App\Http\Livewire\Currency;

use App\Models\Country;
use App\Models\Currency;
use Livewire\Component;

class Edit extends Component
{
    public Currency $currency;

    public function mount(Currency $currency)
    {
        $this->currency = $currency;
    }

    public function render()
    {
        return view('livewire.currency.edit', [
            'countries' => app(Country::class)->all()->pluck('name', 'id')
        ]);
    }

    public function submit()
    {
        $this->validate();

        $this->currency->save();

        return redirect()->route('admin.currencies.index');
    }

    protected function rules(): array
    {
        return [
            'currency.name' => [
                'required',
                'string',
            ],
            'currency.code' => [
                'unique:currencies,code,' . $this->currency->id . ',id,deleted_at,NULL',
                'required',
                'string',
            ],
            'currency.symbol' => [
                'required',
                'string',
            ],
            'currency.country_id' => [
                'required',
                'exists:countries,id'
            ]
        ];
    }
}
