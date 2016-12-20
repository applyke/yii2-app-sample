<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Image;
use frontend\models\ImageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use DateTime;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Image models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImageSearch(['user_id' => Yii::$app->user->identity->id]);
        $model = new Image();
        $dataProvider = $searchModel->search([Yii::$app->request->queryParams]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Image model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Image model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Image();
        $model->file = UploadedFile::getInstance($model, 'file');
        $post = Yii::$app->request->post();
        if ($post && $model->file) {
            $time = new DateTime();
            $path = 'uploads/' . $model->file->baseName . '_'.$time->format("Y-m-d_H_i_s"). '.' . $model->file->extension;
            $model->file->saveAs($path);
            $path = '/'.$path;
            $model->load($post);
            $model->path = $path;
            $model->user_id = Yii::$app->user->identity->id;
            $model->addWatermark();
            $model->save();
            return $this->redirect(['index']);
        } else {
            $searchModel = new ImageSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Image model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Image model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleteImage();
        $model->delete();
        return $this->redirect(['index']);
    }


    /**
     * Rotate the image 90 degrees to the left.
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRotate($id)
    {
        $model = $this->findModel($id);
        $model->rotateImg('90');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
