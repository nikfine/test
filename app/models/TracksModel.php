<?php

namespace models;

use shared\validators\DuplicateValidator;
use shared\validators\TrackStatusTypeValidator;
use yii\db\ActiveRecord;

/**
 * @property int $id                      Первичный ключ;
 * @property string $track_number         Уникальный идентификатор или номер трека;
 * @property string $created_at           Дата и время создания записи (заполняется автоматически);
 * @property string $updated_at           Дата и время последнего обновления записи (заполняется автоматически);
 * @property string $status               Статус трека (может принимать одно из пяти значений).
 */
class TracksModel extends ActiveRecord
{
    /**
     * @return array<mixed>
     */
    public function rules(): array
    {
        return [
            [['track_number', 'status'], 'required'],
            [['track_number', 'status'], 'string'],
            [['status'], TrackStatusTypeValidator::class],
            [
                ['track_number'],
                DuplicateValidator::class,
                'targetClass' => $this,
                'targetAttribute' => 'track_number',
                'filter' => ['!=', 'id', $this->id],
            ],
            [['created_at', 'updated_at', 'id'], 'safe'],
        ];
    }

    public static function tableName(): string
    {
        return 'tracks';
    }
}