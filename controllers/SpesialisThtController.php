<?php

namespace app\controllers;

use app\models\DataPelayanan;
use app\models\MasterPemeriksaanFisik;
use app\models\PenatalaksanaanMcu;
use app\models\spesialis\tht\SpesialisAudiometri;
use Yii;
use app\models\spesialis\tht\SpesialisTht;
use app\models\spesialis\tht\SpesialisThtBerbisik;
use app\models\spesialis\tht\SpesialisThtGarpuTala;
use app\models\spesialis\tht\SpesialisThtSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SpesialisThtController implements the CRUD actions for SpesialisTht model.
 */
class SpesialisThtController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SpesialisTht models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpesialisThtSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SpesialisTht model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SpesialisTht model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SpesialisTht();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_spesialis_tht]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SpesialisTht model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_spesialis_tht]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SpesialisTht model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SpesialisTht model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SpesialisTht the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SpesialisTht::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //------------------------------------------------
    public function actionPeriksa($id = null)
    {

        $id_cari = $id;

        $modelPenata = new PenatalaksanaanMcu();
        if ($id_cari != null) {
            $pasien = DataPelayanan::find()->where(['id_data_pelayanan' => $id_cari])->one();
            if (!$pasien) {
                return $this->redirect(['/site/ngga-nemu', 'id' => $id_cari]);
            }

            $model = SpesialisTht::find()
                ->where(['no_rekam_medik' => $pasien->no_rekam_medik])
                ->andWhere(['no_daftar' => $pasien->no_registrasi])
                ->one();
            if (!$model)
                $model = new SpesialisTht();

            $modelAudiometri = SpesialisAudiometri::find()
                ->where(['no_rekam_medik' => $pasien->no_rekam_medik])
                ->andWhere(['no_daftar' => $pasien->no_registrasi])
                ->one();
            if (!$modelAudiometri)
                $modelAudiometri = new SpesialisAudiometri();

            $modelPenata->no_rekam_medik = $pasien->no_rekam_medik;
            $model->cari_pasien = $id_cari;
            $no_rm = $pasien->no_rekam_medik;
            $no_daftar = $pasien->no_registrasi;
        } else {
            $pasien = null;
            $no_rm = null;
            $no_daftar = null;
            $model = new SpesialisTht();
            $modelAudiometri = new SpesialisAudiometri();
        }
        $modelPenataList = PenatalaksanaanMcu::find()
            ->where(['jenis' => 'spesialis_tht'])
            ->andWhere(['id_fk' => $model->id_spesialis_tht]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $modelAudiometri->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $modelAudiometri->no_rekam_medik = $model->no_rekam_medik;
            $modelAudiometri->no_daftar = $model->no_daftar;

            // echo "<pre>";
            // // print_r($model);
            // print_r($modelAudiometri);
            // echo "</pre>";
            // die;

            if ($model->save() && $modelAudiometri->save()) {
                return [
                    's' => true,
                    'e' => null
                ];
            } else {
                echo "<pre>";
                print_r($model->errors);
                print_r($modelAudiometri->errors);
                echo "</pre>";
                return [
                    's' => false,
                    'e' => $model->errors
                ];
            }
        }

        if ($model->isNewRecord) {
            $model->tl_daun_telinga_kanan = 'Normal';
            $model->tl_daun_telinga_kiri = 'Normal';
            $model->tl_liang_telinga_kanan = 'Normal';
            $model->tl_liang_telinga_kiri = 'Normal';
            $model->tl_serumen_telinga_kanan = 'Tidak Ada';
            $model->tl_serumen_telinga_kiri = 'Tidak Ada';
            $model->tl_membrana_timpani_telinga_kanan = 'Intak';
            $model->tl_membrana_timpani_telinga_kiri = 'Intak';
            $model->tl_test_berbisik_telinga_kanan = 'Normal';
            $model->tl_test_berbisik_telinga_kiri = 'Normal';
            $model->tl_test_berbisik_telinga_kanan_6 = 'Normal';
            $model->tl_test_berbisik_telinga_kiri_6 = 'Normal';
            $model->tl_test_berbisik_telinga_kanan_4 = 'Normal';
            $model->tl_test_berbisik_telinga_kiri_4 = 'Normal';
            $model->tl_test_berbisik_telinga_kanan_3 = 'Normal';
            $model->tl_test_berbisik_telinga_kiri_3 = 'Normal';
            $model->tl_test_berbisik_telinga_kanan_1 = 'Normal';
            $model->tl_test_berbisik_telinga_kiri_1 = 'Normal';
            $model->tl_test_garpu_tala_rinne_telinga_kanan = 'Normal';
            $model->tl_test_garpu_tala_rinne_telinga_kiri = 'Normal';
            // $model->tl_weber_telinga_kanan = 'Normal';
            // $model->tl_weber_telinga_kiri = 'Normal';
            $model->tl_weber_telinga_kanan = 'Tidak Ada Lateralisasi';
            $model->tl_weber_telinga_kiri = 'Tidak Ada Lateralisasi';
            $model->tl_swabach_telinga_kanan = 'Normal';
            $model->tl_swabach_telinga_kiri = 'Normal';
            // $model->tl_bing_telinga_kanan = 'Normal';
            // $model->tl_bing_telinga_kiri = 'Normal';
            $model->hd_meatus_nasi = 'Normal';
            $model->hd_septum_nasi = 'Normal';
            $model->hd_konka_nasal = 'Normal';
            $model->hd_nyeri_ketok_sinus_maksilar = 'Normal';
            $model->hd_penciuman = 'Normal';
            $model->tg_pharynx = 'Normal';
            $model->tg_tonsil_kanan = 'T0';
            $model->tg_tonsil_kiri = 'T0';
            $model->tg_ukuran_kanan = 'Normal';
            $model->tg_ukuran_kiri = 'Normal';
            $model->tg_palatum = 'Normal';

            // gabung berbisik dan garpu tala
            $model->tl_test_berbisik_telinga_kanan_option = 'Jarak 6-5 Meter';
            $model->tl_test_berbisik_telinga_kiri_option = 'Jarak 6-5 Meter';
            $model->tl_test_berbisik_telinga_kanan = 'Dalam Batas Normal';
            $model->tl_test_berbisik_telinga_kiri = 'Dalam Batas Normal';

            // ambil data rinne dari perika audiometri
            $dataAudiometri = SpesialisAudiometri::findOne(['no_rekam_medik' => $no_rm, 'no_daftar' => $no_daftar]);
            if ($dataAudiometri) {
                if ($dataAudiometri->rata_kanan_ac < $dataAudiometri->rata_kanan_bc) {
                    $model->tl_test_garpu_tala_rinne_telinga_kanan = 'Negatif (AC < BC)';
                } else {
                    $model->tl_test_garpu_tala_rinne_telinga_kanan = 'Positif (AC > BC)';
                }
                if ($dataAudiometri->rata_kiri_ac < $dataAudiometri->rata_kiri_bc) {
                    $model->tl_test_garpu_tala_rinne_telinga_kiri = 'Negatif (AC < BC)';
                } else {
                    $model->tl_test_garpu_tala_rinne_telinga_kiri = 'Positif (AC > BC)';
                }
            }

            $model->tl_test_garpu_tala_periksa = 'Ya';
            $model->tl_test_berbisik_periksa = 'Ya';
            $model->tl_audiometri_periksa = 'Ya';

            $model->kesan = 'Normal';
        }

        return $this->render('periksa', [
            'model' => $model,
            'modelAudiometri' => $modelAudiometri,
            'modelPenata' => $modelPenata,
            'modelPenataList' => $modelPenataList,
            'no_rm' => $no_rm,
            'no_daftar' => $no_daftar,
            'pasien' => $pasien,
        ]);
    }

    public function actionCetak($no_rm, $no_daftar)
    {
        $pasien = DataPelayanan::find()->where(['no_rekam_medik' => $no_rm])->andWhere(['no_registrasi' => $no_daftar])->one();

        $model = SpesialisTht::findOne(['no_rekam_medik' => $no_rm, 'no_daftar' => $no_daftar]);
        if (!$model) {
            $model = new SpesialisTht();
        }
        $modelAudiometri = SpesialisAudiometri::findOne(['no_rekam_medik' => $no_rm, 'no_daftar' => $no_daftar]);
        if (!$modelAudiometri) {
            $modelAudiometri = new SpesialisAudiometri();
        }
        // $modelBerbisik = SpesialisThtBerbisik::findOne(['no_rekam_medik' => $no_rm, 'no_daftar' => $no_daftar]);
        // if (!$modelBerbisik) {
        //     $modelBerbisik = new SpesialisThtBerbisik();
        // }
        // $modelGarpuTala = SpesialisThtGarpuTala::findOne(['no_rekam_medik' => $no_rm, 'no_daftar' => $no_daftar]);
        // if (!$modelGarpuTala) {
        //     $modelGarpuTala = new SpesialisThtGarpuTala();
        // }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            // 'format' => 'legal',
            'format' => [210, 330], // F4
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'margin_footer' => 5
        ]);
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->use_kwt = true;
        $mpdf->SetTitle('Spesialis THT ' . $pasien['no_rekam_medik']);
        // return $this->renderPartial('cetak', [
        //     'model' => $model,
        //     'modelAudiometri' => $modelAudiometri,
        //     'modelBerbisik' => $modelBerbisik,
        //     'modelGarpuTala' => $modelGarpuTala,
        //     'no_rm' => $no_rm,
        //     'pasien' => DataPelayanan::find()->where(['no_rekam_medik' => $no_rm])->one(),
        // ]);
        $mpdf->WriteHTML($this->renderPartial('cetak', [
            'model' => $model,
            'modelAudiometri' => $modelAudiometri,
            // 'modelBerbisik' => $modelBerbisik,
            // 'modelGarpuTala' => $modelGarpuTala,
            'no_rm' => $no_rm,
            'pasien' => $pasien,
        ]));
        $mpdf->Output('Spesialis THT ' . $pasien['no_rekam_medik'] . '.pdf', 'I');
        exit;
    }

    // public function actionPeriksaBerbisik($no_rm = null)
    // {
    //     if ($no_rm != null) {
    //         $pasien = DataPelayanan::find()->where(['no_rekam_medik' => $no_rm])->one();
    //         if (!$pasien) {
    //             return $this->redirect(['/site/ngga-nemu', 'no_rm' => $no_rm]);
    //         }
    //         $model = SpesialisThtBerbisik::find()->where(['no_rekam_medik' => $no_rm])->one();
    //         if (!$model)
    //             $model = new SpesialisThtBerbisik();
    //         $model->cari_pasien = $no_rm;
    //     } else {
    //         $pasien = null;
    //         $model = new SpesialisThtBerbisik();
    //     }

    //     if ($model->load(Yii::$app->request->post())) {
    //         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //         if ($model->save()) {
    //             return [
    //                 's' => true,
    //                 'e' => null
    //             ];
    //         } else {
    //             return [
    //                 's' => false,
    //                 'e' => $model->errors
    //             ];
    //         }
    //     }

    //     if ($model->isNewRecord) {
    //         $model->tl_test_berbisik_telinga_kanan_6 = 'Normal';
    //         $model->tl_test_berbisik_telinga_kiri_6 = 'Normal';
    //         $model->tl_test_berbisik_telinga_kanan_4 = 'Normal';
    //         $model->tl_test_berbisik_telinga_kiri_4 = 'Normal';
    //         $model->tl_test_berbisik_telinga_kanan_3 = 'Normal';
    //         $model->tl_test_berbisik_telinga_kiri_3 = 'Normal';
    //         $model->tl_test_berbisik_telinga_kanan_1 = 'Normal';
    //         $model->tl_test_berbisik_telinga_kiri_1 = 'Normal';
    //     }

    //     return $this->render('periksa-berbisik', [
    //         'model' => $model,
    //         'no_rm' => $no_rm,
    //         'pasien' => $pasien,
    //     ]);
    // }

    // public function actionPeriksaGarpuTala($no_rm = null)
    // {
    //     if ($no_rm != null) {
    //         $pasien = DataPelayanan::find()->where(['no_rekam_medik' => $no_rm])->one();
    //         if (!$pasien) {
    //             return $this->redirect(['/site/ngga-nemu', 'no_rm' => $no_rm]);
    //         }
    //         $model = SpesialisThtGarpuTala::find()->where(['no_rekam_medik' => $no_rm])->one();
    //         if (!$model)
    //             $model = new SpesialisThtGarpuTala();
    //         $model->cari_pasien = $no_rm;
    //     } else {
    //         $pasien = null;
    //         $model = new SpesialisThtGarpuTala();
    //     }

    //     if ($model->load(Yii::$app->request->post())) {
    //         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //         if ($model->save()) {
    //             return [
    //                 's' => true,
    //                 'e' => null
    //             ];
    //         } else {
    //             return [
    //                 's' => false,
    //                 'e' => $model->errors
    //             ];
    //         }
    //     }

    //     if ($model->isNewRecord) {
    //         // ambil data rinne dari perika audiometri
    //         $dataAudiometri = SpesialisAudiometri::findOne(['no_rekam_medik' => $no_rm]);
    //         if ($dataAudiometri) {
    //             if ($dataAudiometri->rata_kanan_ac < $dataAudiometri->rata_kanan_bc) {
    //                 $model->tl_test_garpu_tala_rinne_telinga_kanan = 'Negatif (AC < BC)';
    //             } else {
    //                 $model->tl_test_garpu_tala_rinne_telinga_kanan = 'Positif (AC > BC)';
    //             }
    //             if ($dataAudiometri->rata_kiri_ac < $dataAudiometri->rata_kiri_bc) {
    //                 $model->tl_test_garpu_tala_rinne_telinga_kiri = 'Negatif (AC < BC)';
    //             } else {
    //                 $model->tl_test_garpu_tala_rinne_telinga_kiri = 'Positif (AC > BC)';
    //             }
    //         }

    //         $model->tl_weber_telinga_kanan = 'Tidak Ada Lateralisasi';
    //         $model->tl_weber_telinga_kiri = 'Tidak Ada Lateralisasi';
    //         $model->tl_swabach_telinga_kanan = 'Normal';
    //         $model->tl_swabach_telinga_kiri = 'Normal';
    //         // $model->tl_bing_telinga_kanan = 'Normal';
    //         // $model->tl_bing_telinga_kiri = 'Normal';
    //     }

    //     return $this->render('periksa-garpu-tala', [
    //         'model' => $model,
    //         'no_rm' => $no_rm,
    //         'pasien' => $pasien,
    //     ]);
    // }

    public function actionPeriksaBerbisik($id = null)
    {

        $id_cari = $id;

        $modelPenata = new PenatalaksanaanMcu();
        if ($id_cari != null) {
            $pasien = DataPelayanan::find()->where(['id_data_pelayanan' => $id_cari])->one();
            if (!$pasien) {
                return $this->redirect(['/site/ngga-nemu', 'id' => $id_cari]);
            }

            $model = SpesialisThtBerbisik::find()
                ->where(['no_rekam_medik' => $pasien->no_rekam_medik])
                ->andWhere(['no_daftar' => $pasien->no_registrasi])
                ->one();
            if (!$model)
                $model = new SpesialisThtBerbisik();

            $modelPenata->no_rekam_medik = $pasien->no_rekam_medik;
            $model->cari_pasien = $id_cari;
            $no_rm = $pasien->no_rekam_medik;
            $no_daftar = $pasien->no_registrasi;
        } else {
            $pasien = null;
            $no_rm = null;
            $no_daftar = null;
            $model = new SpesialisThtBerbisik();
        }
        $modelPenataList = PenatalaksanaanMcu::find()
            ->where(['jenis' => 'spesialis_tht_berbisik'])
            ->andWhere(['id_fk' => $model->id_spesialis_tht_berbisik]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->save()) {
                return [
                    's' => true,
                    'e' => null
                ];
            } else {
                return [
                    's' => false,
                    'e' => $model->errors
                ];
            }
        }

        if ($model->isNewRecord) {
            // $model->tl_test_berbisik_telinga_kanan_6 = 'Normal';
            // $model->tl_test_berbisik_telinga_kiri_6 = 'Normal';
            // $model->tl_test_berbisik_telinga_kanan_4 = 'Normal';
            // $model->tl_test_berbisik_telinga_kiri_4 = 'Normal';
            // $model->tl_test_berbisik_telinga_kanan_3 = 'Normal';
            // $model->tl_test_berbisik_telinga_kiri_3 = 'Normal';
            // $model->tl_test_berbisik_telinga_kanan_1 = 'Normal';
            // $model->tl_test_berbisik_telinga_kiri_1 = 'Normal';
            $model->tl_test_berbisik_telinga_kanan_option = 'Jarak 6-5 Meter';
            $model->tl_test_berbisik_telinga_kiri_option = 'Jarak 6-5 Meter';
            $model->tl_test_berbisik_telinga_kanan = 'Dalam Batas Normal';
            $model->tl_test_berbisik_telinga_kiri = 'Dalam Batas Normal';
            $model->kesan = 'Normal';
        }

        return $this->render('periksa-berbisik', [
            'model' => $model,
            'modelPenata' => $modelPenata,
            'modelPenataList' => $modelPenataList,
            'no_rm' => $no_rm,
            'no_daftar' => $no_daftar,
            'pasien' => $pasien,
        ]);
    }

    public function actionCetakBerbisik($no_rm, $no_daftar)
    {
        $pasien = DataPelayanan::find()->where(['no_rekam_medik' => $no_rm])->andWhere(['no_registrasi' => $no_daftar])->one();
        $modelBerbisik = SpesialisThtBerbisik::findOne(['no_rekam_medik' => $no_rm, 'no_daftar' => $no_daftar]);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'legal',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 10,
            'margin_footer' => 5
        ]);
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->use_kwt = true;
        $mpdf->SetTitle('Tes Berbisik ' . $pasien['no_rekam_medik']);
        // return $this->renderPartial('cetak', [
        //     'model' => $model,
        //     'modelAudiometri' => $modelAudiometri,
        //     'modelBerbisik' => $modelBerbisik,
        //     'modelGarpuTala' => $modelGarpuTala,
        //     'no_rm' => $no_rm,
        //     'pasien' => DataPelayanan::find()->where(['no_rekam_medik' => $no_rm])->one(),
        // ]);
        $mpdf->WriteHTML($this->renderPartial('cetak-berbisik', [
            'modelBerbisik' => $modelBerbisik,
            'no_rm' => $no_rm,
            'pasien' => $pasien,
        ]));
        $mpdf->Output('Tes Berbisik ' . $pasien['no_rekam_medik'] . '.pdf', 'I');
        exit;
    }

    public function actionSimpanPenata($id = null)
    {
        $model = new PenatalaksanaanMcu();

        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model->jenis = 'spesialis_tht';
            $model->id_fk = $id;

            if ($model->save()) {
                return [
                    's' => true,
                    'e' => null
                ];
            } else {
                return [
                    's' => false,
                    'e' => $model->errors
                ];
            }
        }
    }

    public function actionSimpanPenataBerbisik($id = null)
    {
        $model = new PenatalaksanaanMcu();

        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model->jenis = 'spesialis_tht_berbisik';
            $model->id_fk = $id;

            if ($model->save()) {
                return [
                    's' => true,
                    'e' => null
                ];
            } else {
                return [
                    's' => false,
                    'e' => $model->errors
                ];
            }
        }
    }

    public function actionPeriksaGarpuTala($id = null)
    {

        $id_cari = $id;

        $modelPenata = new PenatalaksanaanMcu();
        if ($id_cari != null) {
            $pasien = DataPelayanan::find()->where(['id_data_pelayanan' => $id_cari])->one();
            if (!$pasien) {
                return $this->redirect(['/site/ngga-nemu', 'id' => $id_cari]);
            }

            $model = SpesialisThtGarpuTala::find()
                ->where(['no_rekam_medik' => $pasien->no_rekam_medik])
                ->andWhere(['no_daftar' => $pasien->no_registrasi])
                ->one();
            if (!$model)
                $model = new SpesialisThtGarpuTala();

            $modelPenata->no_rekam_medik = $pasien->no_rekam_medik;
            $model->cari_pasien = $id_cari;
            $no_rm = $pasien->no_rekam_medik;
            $no_daftar = $pasien->no_registrasi;
        } else {
            $pasien = null;
            $no_rm = null;
            $no_daftar = null;
            $model = new SpesialisThtGarpuTala();
        }
        $modelPenataList = PenatalaksanaanMcu::find()
            ->where(['jenis' => 'spesialis_tht_garpu_tala'])
            ->andWhere(['id_fk' => $model->id_spesialis_tht_garpu_tala]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->save()) {
                return [
                    's' => true,
                    'e' => null
                ];
            } else {
                return [
                    's' => false,
                    'e' => $model->errors
                ];
            }
        }

        if ($model->isNewRecord) {
            // ambil data rinne dari perika audiometri
            $dataAudiometri = SpesialisAudiometri::findOne(['no_rekam_medik' => $no_rm]);
            if ($dataAudiometri) {
                if ($dataAudiometri->rata_kanan_ac < $dataAudiometri->rata_kanan_bc) {
                    $model->tl_test_garpu_tala_rinne_telinga_kanan = 'Negatif (AC < BC)';
                } else {
                    $model->tl_test_garpu_tala_rinne_telinga_kanan = 'Positif (AC > BC)';
                }
                if ($dataAudiometri->rata_kiri_ac < $dataAudiometri->rata_kiri_bc) {
                    $model->tl_test_garpu_tala_rinne_telinga_kiri = 'Negatif (AC < BC)';
                } else {
                    $model->tl_test_garpu_tala_rinne_telinga_kiri = 'Positif (AC > BC)';
                }
            }

            $model->tl_weber_telinga_kanan = 'Tidak Ada Lateralisasi';
            $model->tl_weber_telinga_kiri = 'Tidak Ada Lateralisasi';
            $model->tl_swabach_telinga_kanan = 'Normal';
            $model->tl_swabach_telinga_kiri = 'Normal';
            // $model->tl_bing_telinga_kanan = 'Normal';
            // $model->tl_bing_telinga_kiri = 'Normal';
            $model->kesan = 'Normal';
        }

        return $this->render('periksa-garpu-tala', [
            'model' => $model,
            'modelPenata' => $modelPenata,
            'modelPenataList' => $modelPenataList,
            'no_rm' => $no_rm,
            'no_daftar' => $no_daftar,
            'pasien' => $pasien,
        ]);
    }

    public function actionCetakGarpuTala($no_rm, $no_daftar)
    {
        $pasien = DataPelayanan::find()->where(['no_rekam_medik' => $no_rm])->andWhere(['no_registrasi' => $no_daftar])->one();
        $modelGarpuTala = SpesialisThtGarpuTala::findOne(['no_rekam_medik' => $no_rm, 'no_daftar' => $no_daftar]);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'legal',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 10,
            'margin_footer' => 5
        ]);
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->use_kwt = true;
        $mpdf->SetTitle('Tes GarpuTala ' . $pasien['no_rekam_medik']);
        // return $this->renderPartial('cetak', [
        //     'model' => $model,
        //     'modelAudiometri' => $modelAudiometri,
        //     'modelGarpuTala' => $modelGarpuTala,
        //     'modelGarpuTala' => $modelGarpuTala,
        //     'no_rm' => $no_rm,
        //     'pasien' => DataPelayanan::find()->where(['no_rekam_medik' => $no_rm])->one(),
        // ]);
        $mpdf->WriteHTML($this->renderPartial('cetak-garpu-tala', [
            'modelGarpuTala' => $modelGarpuTala,
            'no_rm' => $no_rm,
            'pasien' => $pasien,
        ]));
        $mpdf->Output('Tes Berbisik ' . $pasien['no_rekam_medik'] . '.pdf', 'I');
        exit;
    }

    public function actionSimpanPenataGarpuTala($id = null)
    {
        $model = new PenatalaksanaanMcu();

        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model->jenis = 'spesialis_tht_garpu_tala';
            $model->id_fk = $id;

            if ($model->save()) {
                return [
                    's' => true,
                    'e' => null
                ];
            } else {
                return [
                    's' => false,
                    'e' => $model->errors
                ];
            }
        }
    }
}
