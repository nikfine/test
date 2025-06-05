<?php

namespace modules\tracks\services;

use models\TracksModel;
use shared\exceptions\ValidateException;
use Throwable;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

final readonly class TracksService
{
    public function __construct(private TracksModel $tracksModel = new TracksModel())
    {
    }

    /**
     * @throws NotFoundHttpException
     */
    public function get(int $id): TracksModel
    {
        return $this->tracksModel->findOne($id) ?? throw new NotFoundHttpException("Track {$id} not found");
    }

    /**
     * @param string|array<mixed>|null $status
     * @return array<TracksModel>
     */
    public function list(string|array|null $status = null): array
    {
        /** @var array<TracksModel> $tracks */
        $tracks = $this
            ->tracksModel
            ->find()
            ->filterWhere(['status' => $status ?: null])
            ->all();
        return $tracks;
    }

    /**
     * @param array<mixed> $data
     * @return void
     * @throws Exception
     * @throws ValidateException
     */
    public function create(array $data): void
    {
        $track = new $this->tracksModel();
        $track->setAttributes($data);
        if (!$track->validate()) {
            throw new ValidateException($track->getErrors());
        }
        $track->save();
    }

    /**
     * @param int $id
     * @param array<mixed> $data
     * @return void
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function update(int $id, array $data): void
    {
        $track = $this->get($id);
        $track->setAttributes($data);
        if (!$track->validate()) {
            throw new ValidateException($track->getErrors());
        }
        $track->save();
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function delete(int $id): void
    {
        $track = $this->get($id);
        $track->delete();
    }
}