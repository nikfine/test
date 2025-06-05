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
use yii\rest\Controller;
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
     * @throws HttpException
     */
    public function actionIndex(): array
    {
        $request = $this->request->get('status');
        if (!\is_string($request) && !\is_null($request) && !\is_array($request)) {
            throw new HttpException(400, 'Invalid request');
        }
        return new TracksService()->list($request);
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
     * @throws HttpException
     * @api
     */
    public function actionCreate(): array|null
    {
        try {
            $request = $this->request->post();
            if (!\is_array($request)) {
                throw new HttpException(400, 'Invalid request');
            }
            new TracksService()->create($request);
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
            $request = $this->request->post();
            if (!\is_array($request)) {
                throw new HttpException(400, 'Invalid request');
            }
            new TracksService()->update($id, $request);
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