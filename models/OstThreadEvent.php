<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ost_thread_event".
 *
 * @property string $id
 * @property string $thread_id
 * @property string $staff_id
 * @property string $team_id
 * @property string $dept_id
 * @property string $topic_id
 * @property string $state
 * @property string $data Encoded differences
 * @property string $username
 * @property string $uid
 * @property string $uid_type
 * @property int $annulled
 * @property string $timestamp
 */
class OstThreadEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ost_thread_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thread_id', 'staff_id', 'team_id', 'dept_id', 'topic_id', 'uid', 'annulled'], 'integer'],
            [['staff_id', 'team_id', 'dept_id', 'topic_id', 'state', 'timestamp'], 'required'],
            [['state'], 'string'],
            [['timestamp'], 'safe'],
            [['data'], 'string', 'max' => 1024],
            [['username'], 'string', 'max' => 128],
            [['uid_type'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thread_id' => 'Thread ID',
            'staff_id' => 'Staff ID',
            'team_id' => 'Team ID',
            'dept_id' => 'Dept ID',
            'topic_id' => 'Topic ID',
            'state' => 'State',
            'data' => 'Data',
            'username' => 'Username',
            'uid' => 'Uid',
            'uid_type' => 'Uid Type',
            'annulled' => 'Annulled',
            'timestamp' => 'Timestamp',
        ];
    }
}
