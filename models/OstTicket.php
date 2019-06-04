<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ost_ticket".
 *
 * @property string $ticket_id
 * @property string $number
 * @property string $user_id
 * @property string $user_email_id
 * @property string $status_id
 * @property string $dept_id
 * @property string $sla_id
 * @property string $topic_id
 * @property string $staff_id
 * @property string $team_id
 * @property string $email_id
 * @property string $lock_id
 * @property string $flags
 * @property string $ip_address
 * @property string $source
 * @property string $source_extra
 * @property int $isoverdue
 * @property int $isanswered
 * @property string $duedate
 * @property string $est_duedate
 * @property string $reopened
 * @property string $closed
 * @property string $lastupdate
 * @property string $created
 * @property string $updated
 */
class OstTicket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ost_ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_email_id', 'status_id', 'dept_id', 'sla_id', 'topic_id', 'staff_id', 'team_id', 'email_id', 'lock_id', 'flags', 'isoverdue', 'isanswered'], 'integer'],
            [['source'], 'string'],
            [['duedate', 'est_duedate', 'reopened', 'closed', 'lastupdate', 'created', 'updated'], 'safe'],
            [['created', 'updated'], 'required'],
            [['number'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 64],
            [['source_extra'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ticket_id' => 'Ticket ID',
            'number' => 'Number',
            'user_id' => 'User ID',
            'user_email_id' => 'User Email ID',
            'status_id' => 'Status ID',
            'dept_id' => 'Dept ID',
            'sla_id' => 'Sla ID',
            'topic_id' => 'Topic ID',
            'staff_id' => 'Staff ID',
            'team_id' => 'Team ID',
            'email_id' => 'Email ID',
            'lock_id' => 'Lock ID',
            'flags' => 'Flags',
            'ip_address' => 'Ip Address',
            'source' => 'Source',
            'source_extra' => 'Source Extra',
            'isoverdue' => 'Isoverdue',
            'isanswered' => 'Isanswered',
            'duedate' => 'Duedate',
            'est_duedate' => 'Est Duedate',
            'reopened' => 'Reopened',
            'closed' => 'Closed',
            'lastupdate' => 'Lastupdate',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
