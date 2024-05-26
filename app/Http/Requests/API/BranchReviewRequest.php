<?php

namespace App\Http\Requests\API;

class BranchReviewRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'rate' => 'required',
            'comment' => 'required',
        ];
    }
}
