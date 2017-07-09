<?php
namespace App\Transformer;

use App\User;
use League\Fractal;

class NoteTransformer extends Fractal\TransformerAbstract
{
    public function transform($note)
    {
        return [
            'id'      => (int) $note->id,
            'title'   => $note->title,
            'note'    => $note->note,
            'created_at'    => $note->created_at,
            'updated_at'    => $note->updated_at,
            'creator'   => [
                [
                    'id' => (int) $note->user_id
                ]
            ],
        ];
    }
}