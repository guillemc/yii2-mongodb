<?php

namespace yiiunit\extensions\mongodb\validators;

use yii\base\Model;
use yii\mongodb\validators\MongoDateValidator;
use yiiunit\extensions\mongodb\TestCase;

class MongoDateValidatorTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function testValidateValue()
    {
        $validator = new MongoDateValidator();
        $this->assertFalse($validator->validate('string'));
        $this->assertTrue($validator->validate(new \MongoDB\BSON\UTCDatetime(time())));
    }

    public function testValidateAttribute()
    {
        $model = new MongoDateTestModel();

        $model->date = 'string';
        $this->assertFalse($model->validate());
        $model->date = new \MongoDB\BSON\UTCDatetime(time());
        $this->assertTrue($model->validate());
    }

    public function testMongoDateAttribute()
    {
        $model = new MongoDateTestModel();

        $model->date = '05/08/2015';
        $this->assertTrue($model->validate());
        $this->assertTrue($model->mongoDate instanceof \MongoDB\BSON\UTCDatetime);
        $this->assertEquals(strtotime('2015-05-08'), $model->mongoDate->toDatetime()->format('U'));
    }
}

class MongoDateTestModel extends Model
{
    public $date;
    public $mongoDate;

    public function rules()
    {
        return [
            ['date', MongoDateValidator::className(), 'format' => 'MM/dd/yyyy', 'mongoDateAttribute' => 'mongoDate']
        ];
    }
}