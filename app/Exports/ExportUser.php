<?php

namespace App\Exports;

use App\Http\Models\UserModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUser implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct($id)
    {
        $this->id = $id;
    }
    public function query()
    {
        return UserModel::query()->select('first_name','last_name','email','phone')->where('id_department','=',$this->id);
    }
    
    public function headings(): array
    {
        return ['First Name','Last Name','Email','Phone'];
    }
}
