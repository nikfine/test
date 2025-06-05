<?php

namespace unit;

use Codeception\Test\Unit;
use models\TracksModel;
use modules\tracks\services\TracksService;
use shared\exceptions\ValidateException;
use shared\types\TrackStatusType;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

class TracksTest extends Unit
{
    /**
     * @throws NotFoundHttpException
     */
    public function testGet()
    {
        $mock = new class extends TracksModel {
            public static function findOne($condition)
            {
                $model = new TracksModel();
                $model->status = TrackStatusType::NEW;
                $model->track_number = 'num1';
                return $model;
            }
        };
        $track = new TracksService($mock)->get(1);
        $this->assertEquals(TrackStatusType::NEW, $track->status);
        $this->assertEquals('num1', $track->track_number);
    }

    public function testGetNotFound()
    {
        $mock = new class extends TracksModel {
            public static function findOne($condition)
            {
                return null;
            }
        };
        $this->expectException(NotFoundHttpException::class);
        new TracksService($mock)->get(1);
    }

    public function testList()
    {
        $mock = new class extends TracksModel {
            public static function find()
            {
                return new class(TracksModel::class) extends ActiveQuery
                {
                    public function all($db = null)
                    {
                        return [
                            new TracksModel(),
                            new TracksModel(),
                        ];
                    }
                };
            }
        };
        $tracks = new TracksService($mock)->list();
        $this->assertCount(2, $tracks);
    }

    public function testCreate()
    {
        $mock = new class extends TracksModel {
            public static function findOne($condition)
            {
                return new class extends TracksModel{
                    public function save($runValidation = true, $attributeNames = null)
                    {
                        return true;
                    }
                };
            }
        };
        try {
            new TracksService($mock)->create(['status' => TrackStatusType::NEW->value, 'track_number' => 'num1']);
        } catch (ValidateException $e) {
            $this->fail(var_export($e->getErrors(), true));
        }
    }

    public function testCreateInvalid()
    {
        $mock = new class extends TracksModel {
            public static function findOne($condition)
            {
                return new class extends TracksModel
                {
                    public function save($runValidation = true, $attributeNames = null)
                    {
                        return true;
                    }
                };
            }
        };
        $this->expectException(ValidateException::class);
        new TracksService($mock)->create(['status' => 'test', 'track_number' => 'num1']);
    }

    public function testUpdate()
    {
        $mock = new class extends TracksModel {
            public static function findOne($condition)
            {
                return new class extends TracksModel
                {
                    public function save($runValidation = true, $attributeNames = null)
                    {
                        return true;
                    }
                };
            }
        };
        try {
            new TracksService($mock)->create(['status' => TrackStatusType::COMPLETED->value, 'track_number' => 'num1']);
        } catch (ValidateException $e) {
            $this->fail(var_export($e->getErrors(), true));
        }
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function testUpdateInvalid()
    {
        $mock = new class extends TracksModel {
            public static function findOne($condition)
            {
                return new class extends TracksModel
                {
                    public function save($runValidation = true, $attributeNames = null)
                    {
                        return true;
                    }
                };
            }
        };
        $this->expectException(ValidateException::class);
        new TracksService($mock)->update(1, ['status' => 'test', 'track_number' => 'num1']);
    }
}