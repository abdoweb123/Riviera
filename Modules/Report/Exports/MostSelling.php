<?php

namespace Modules\Report\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MostSelling implements FromView
{
    protected $MostSelling;

    public function __construct(array $MostSelling)
    {
        $this->MostSelling = $MostSelling[0];
    }

    public function view(): View
    {
        return view('report::exports.mostselling', [
            'MostSelling' => $this->MostSelling,
        ]);
    }
}
