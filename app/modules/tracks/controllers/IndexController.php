<?php

namespace modules\tracks\controllers;

use models\TracksModel;
use modules\tracks\services\TracksService;
use shared\auth\HttpBearerAuth;
use shared\exceptions\ValidateException;
use Throwable;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\auth\CompositeAuth;
use shared\rest\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * @api
 */
final class IndexController extends Controller
{
    /**
     * @return array<mixed>
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                ['class' => HttpBearerAuth::class],
            ],
        ];

        return $behaviors;
    }

    /**
     * @return array<TracksModel>
     */
    public function actionIndex(): array
    {
        return new TracksService()->list($this->request->get('status'));
    }

    /**
     * @param int $id
     * @api
     * @return TracksModel
     * @throws NotFoundHttpException
     */
    public function actionGet(int $id): TracksModel
    {
        return new TracksService()->get($id);
    }

    /**
     * @return array<mixed>|null
     * @throws Exception
     * @api
     */
    public function actionCreate(): array|null
    {
        try {
            new TracksService()->create($this->request->post());
            return null;
        } catch (ValidateException $e) {
            return $e->getErrors();
        }
    }

    /**
     * @param int $id
     * @return array<mixed>|null
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws HttpException
     * @api
     */
    public function actionUpdate(int $id): array|null
    {
        try {
            new TracksService()->update($id, $this->request->post());
            return null;
        } catch (ValidateException $e) {
            return $e->getErrors();
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @api
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): void
    {
        new TracksService()->delete($id);
    }
}