<?php

namespace app\models\spesialis\narkoba;

use Yii;

/**
 * This is the model class for table "mcu.spesialis_narkoba".
 *
 * @property int $id_spesialis_narkoba
 * @property string $no_rekam_medik
 * @property string $no_daftar
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $benzodiazepin_hasil
 * @property string|null $benzodiazepin_keterangan
 * @property string|null $thc_hasil
 * @property string|null $thc_keterangan
 * @property string|null $opiat_hasil
 * @property string|null $opiat_keterangan
 * @property string|null $amphetammin_hasil
 * @property string|null $amphetammin_keterangan
 * @property string|null $kokain_hasil
 * @property string|null $kokain_keterangan
 * @property string|null $methamphetamin_hasil
 * @property string|null $methamphetamin_keterangan
 * @property string|null $carisoprodol_hasil
 * @property string|null $carisoprodol_keterangan
 */
class SpesialisNarkoba extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mcu.spesialis_narkoba';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_rekam_medik', 'no_daftar'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'default', 'value' => null],
            [['created_by', 'updated_by'], 'integer'],
            [['no_rekam_medik', 'no_daftar'], 'string', 'max' => 120],
            [['benzodiazepin_hasil', 'thc_hasil', 'opiat_hasil', 'amphetammin_hasil', 'kokain_hasil', 'methamphetamin_hasil', 'carisoprodol_hasil'], 'string', 'max' => 30],
            [['benzodiazepin_keterangan', 'thc_keterangan', 'opiat_keterangan', 'amphetammin_keterangan', 'kokain_keterangan', 'methamphetamin_keterangan', 'carisoprodol_keterangan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_spesialis_narkoba' => 'Id Spesialis Narkoba',
            'no_rekam_medik' => 'No Rekam Medik',
            'no_daftar' => 'No Daftar',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'benzodiazepin_hasil' => 'Benzodiazepin Hasil',
            'benzodiazepin_keterangan' => 'Benzodiazepin Keterangan',
            'thc_hasil' => 'Thc Hasil',
            'thc_keterangan' => 'Thc Keterangan',
            'opiat_hasil' => 'Opiat Hasil',
            'opiat_keterangan' => 'Opiat Keterangan',
            'amphetammin_hasil' => 'Amphetammin Hasil',
            'amphetammin_keterangan' => 'Amphetammin Keterangan',
            'kokain_hasil' => 'Kokain Hasil',
            'kokain_keterangan' => 'Kokain Keterangan',
            'methamphetamin_hasil' => 'Methamphetamin Hasil',
            'methamphetamin_keterangan' => 'Methamphetamin Keterangan',
            'carisoprodol_hasil' => 'Carisoprodol Hasil',
            'carisoprodol_keterangan' => 'Carisoprodol Keterangan',
        ];
    }
}
